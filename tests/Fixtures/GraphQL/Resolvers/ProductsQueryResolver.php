<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Resolvers;



use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\ProductType;

final readonly class ProductsQueryResolver
{
    /**
     * @return ProductType[]
     * @noinspection PhpUnusedParameterInspection
     */
    public function resolve(string $searchString = null):array{

        return [new ProductType()];
    }

}
