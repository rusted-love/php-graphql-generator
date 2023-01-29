<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver\FieldResolvers;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\FieldResolver\FieldResolverInfo;
use BladL\BestGraphQL\FieldResolver\FieldResolverAbstract;
use function call_user_func_array;

/**
 * @internal
 */
final readonly class OperationFieldResolver extends FieldResolverAbstract
{

    public function supports(FieldResolverInfo $info): bool
    {
        $parentTypeName = $info->getParentTypeName();
        return 'Query' === $parentTypeName || 'Mutation' === $parentTypeName || 'Subscription' === $parentTypeName;
    }

    /**
     * @throws ResolverException
     */
    protected function proceedSerialize(FieldResolverInfo $info): mixed
    {
        $fieldName = $info->getFieldName();
        $parentTypeName = $info->getParentTypeName();
        $class = $this->schemaResolverConfig->operationConfig->getResolverClass(operationName: $parentTypeName, fieldName: $fieldName);
        if (null === $class) {
            throw new ResolverException("Class for $parentTypeName not exist");
        }
        \assert(\class_exists($class));
        $resolver = $this->schemaResolverConfig->getAutoWired($class);
        \assert(\is_object($resolver));
        if (method_exists($resolver, 'resolve')) {
            return call_user_func_array([$resolver, 'resolve'], $info->args);
        }

        throw new ResolverException("Field $fieldName not found in class $class");
    }
}
