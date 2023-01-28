<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeMapper;

interface TypeMapperInterface
{
    public function toOutputType(mixed $value):mixed;
}
