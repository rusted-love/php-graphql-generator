<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeMapper\TypeMappers;

use BladL\BestGraphQL\TypeMapper\TypeMapperAbstract;
use function is_array;

final readonly class ListTypeMapper extends TypeMapperAbstract
{

    public function toOutputType(mixed $value): mixed
    {
        if (is_array($value) && array_is_list($value)) {
            return [...array_map(fn(mixed $item) => $this->makeNewRootMapper()->toOutputType($value), $value)];
        }
        return $value;
    }
}
