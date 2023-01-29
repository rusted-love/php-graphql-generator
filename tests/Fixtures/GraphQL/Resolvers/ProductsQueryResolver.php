<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Resolvers;



use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\ProductType;
use BladL\BestGraphQL\Tests\Fixtures\Services\SomeAmazingService;

final readonly class ProductsQueryResolver
{
    public function __construct(public SomeAmazingService $service)
    {
    }

    /**
     * @return ProductType[]
     * @noinspection PhpUnusedParameterInspection
     */
    public function resolve(string $searchString = null):array{

        return [new ProductType('product_'.$this->service->getOne())];
    }

}
