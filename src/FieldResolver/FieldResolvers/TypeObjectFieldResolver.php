<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver\FieldResolvers;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\FieldResolver\FieldResolverInfo;
use BladL\BestGraphQL\FieldResolver\FieldResolverAbstract;
use BladL\BestGraphQL\Utils;
use UnitEnum;
use function assert;
use function call_user_func_array;
use function is_callable;
use function is_object;

/**
 * @internal
 */
final readonly class TypeObjectFieldResolver extends FieldResolverAbstract
{

    public function supports(FieldResolverInfo $info): bool
    {
        return is_object($info->objectValue) && $this->schemaResolverConfig->typesConfig->isTypeClassExists($info->getParentTypeName());
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

        return $this->formatArray($value);
    }

    private function formatArray(mixed $value): mixed
    {
        if (Utils::valueIsList($value)) {
            $value = array_map(static function (mixed $item) {
                if ($item instanceof UnitEnum) {
                    return $item->name;
                }
                return $item;
            }, $value);
        }
        return $value;
    }
}
