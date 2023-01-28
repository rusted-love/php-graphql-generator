<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer\Serializers;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\Serializer\FieldResolverInfo;
use BladL\BestGraphQL\Serializer\SerializerAbstract;
use function assert;
use function call_user_func_array;
use function is_callable;
use function is_object;

final readonly class ObjectSerializer extends SerializerAbstract
{

    public function supports(FieldResolverInfo $info): bool
    {
        return is_object($info->objectValue);
    }

    /**
     * @throws ResolverException
     */
    protected function proceedSerialize(FieldResolverInfo $info): mixed
    {
        $fieldName = $info->getFieldName();
        $value = $info->objectValue;
        assert(is_object($value));
        if (method_exists($value, $fieldName)) {
            $callable = [$value, $fieldName];
            assert(is_callable($callable));
            $value = call_user_func_array($callable, $info->args);
        } elseif (property_exists($value, $fieldName)) {
            $value = $value->{$fieldName};
        } else {
            throw new ResolverException("Field $fieldName not found in " . $value::class);
        }

        return $value;
    }
}
