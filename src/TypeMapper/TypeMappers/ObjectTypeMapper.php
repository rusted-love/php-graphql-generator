<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeMapper\TypeMappers;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\TypeMapper\TypeMapperAbstract;
use function assert;
use function call_user_func_array;
use function is_callable;
use function is_object;

final readonly class ObjectTypeMapper extends TypeMapperAbstract
{
    /**
     * @throws ResolverException
     */
    public function toOutputType(mixed $value): mixed
    {
        $fieldName = $this->getFieldName();
        if (is_object($value)) {
            if (method_exists($value, $fieldName)) {
                $callable = [$value, $fieldName];
                assert(is_callable($callable));
                $value = call_user_func_array($callable, $this->getFieldArguments());
            } elseif (property_exists($value, $fieldName)) {
                $value = $value->{$fieldName};
            } else {
                throw new ResolverException("Field $fieldName not found in " . $value::class);
            }
        }
        return $value;
    }
}
