<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver\FieldResolvers;

use BladL\BestGraphQL\Exception\FieldResolverException;
use BladL\BestGraphQL\FieldResolver\FieldResolverAbstract;
use BladL\BestGraphQL\FieldResolver\FieldResolverCollection;
use BladL\BestGraphQL\FieldResolver\FieldResolverInfo;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use function is_array;

final readonly class ListFieldResolver extends FieldResolverAbstract
{
    /**
     * @return array<int,mixed>
     * @throws FieldResolverException
     */
    protected function proceedResolve(FieldResolverInfo $info): array
    {
        $value = $info->objectValue;
        \assert(is_array($value) && array_is_list($value));
        if (!$info->info->returnType instanceof ListOfType) {
            throw new FieldResolverException('Invalid value returned in '.implode('->',$info->info->path).':'.$info->info->returnType->toString().' received '.\gettype($value));
        }
        return$value;
    }

    public function supports(FieldResolverInfo $info): bool
    {
        $value = $info->objectValue;
        return is_array($value) && array_is_list($value);
    }
}
