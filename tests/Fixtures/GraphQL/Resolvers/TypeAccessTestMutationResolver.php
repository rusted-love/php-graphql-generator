<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Resolvers;

final class TypeAccessTestMutationResolver
{
    public function resolve():int{
        return 1;
    }
}
