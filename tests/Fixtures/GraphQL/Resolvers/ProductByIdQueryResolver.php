<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Resolvers;

use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\ProductType;

final readonly class ProductByIdQueryResolver
{
    public function resolve(string $id): ProductType
    {

        return new ProductType($id);
    }

}
