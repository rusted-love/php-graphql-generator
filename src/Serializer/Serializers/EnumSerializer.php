<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer\Serializers;

use BladL\BestGraphQL\Serializer\FieldResolverInfo;
use BladL\BestGraphQL\Serializer\SerializerAbstract;
use UnitEnum;

final  readonly class EnumSerializer extends SerializerAbstract
{
    protected function proceedSerialize(FieldResolverInfo $info): mixed
    {
        $value = $info->objectValue;
        if ($value instanceof UnitEnum) {
            $value = $value->name;
        }
        return $value;
    }

    public function supports(FieldResolverInfo $info): bool
    {
       return $info->objectValue instanceof UnitEnum;
    }
}
