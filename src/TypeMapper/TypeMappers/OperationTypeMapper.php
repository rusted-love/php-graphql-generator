<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeMapper\TypeMappers;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\TypeMapper\TypeMapperAbstract;
use function call_user_func_array;

final readonly class OperationTypeMapper extends TypeMapperAbstract
{

    /**
     * @throws ResolverException
     */
    public function toOutputType(mixed $value): mixed
    {
        $parentTypeName = $this->getParentType()->name();
        $fieldName = $this->getFieldName();
        if ('Query' === $parentTypeName || 'Mutation' === $parentTypeName) {
            $class = $this->getNamespace() . 'Resolvers\\' . ucfirst($fieldName) . $parentTypeName . 'Resolver';
            if (!class_exists($class)) {
                throw new ResolverException("Class $class not found");
            }
            $resolver = $this->autoWireClass($class);
            if (method_exists($resolver, 'resolve')) {
                return call_user_func_array([$resolver, 'resolve'], $this->getFieldArguments());
            }

            throw new ResolverException("Field $fieldName not found in class $class");
        }
        return $value;
    }
}
