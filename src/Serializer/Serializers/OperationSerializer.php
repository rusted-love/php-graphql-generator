<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer\Serializers;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\Serializer\FieldResolverInfo;
use BladL\BestGraphQL\Serializer\SerializerAbstract;
use function call_user_func_array;

final readonly class OperationSerializer extends SerializerAbstract
{

    public function supports(FieldResolverInfo $info): bool
    {
        $parentTypeName = $info->getParentTypeName();
        return 'Query' === $parentTypeName || 'Mutation' === $parentTypeName;
    }

    /**
     * @throws ResolverException
     */
    protected function proceedSerialize(FieldResolverInfo $info): mixed
    {
        $fieldName = $info->getFieldName();
        $parentTypeName = $info->getParentTypeName();
        $class = $this->getNamespace() . 'Resolvers\\' . ucfirst($fieldName) . $parentTypeName . 'Resolver';
        if (!class_exists($class)) {
            throw new ResolverException("Class $class not found");
        }
        $resolver = $this->autoWireClass($class);
        if (method_exists($resolver, 'resolve')) {
            return call_user_func_array([$resolver, 'resolve'], $info->args);
        }

        throw new ResolverException("Field $fieldName not found in class $class");
    }
}
