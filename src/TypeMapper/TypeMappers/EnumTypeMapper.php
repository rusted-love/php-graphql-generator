<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeMapper\TypeMappers;

use BladL\BestGraphQL\TypeMapper\TypeMapperAbstract;

final  readonly class EnumTypeMapper extends TypeMapperAbstract
{
    public function toOutputType(mixed $value): mixed
    {
        if ($value instanceof \UnitEnum) {
            $value = $value->name;
        }
        return $value;
    }
}
