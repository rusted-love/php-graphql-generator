<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Resolvers;

use BladL\BestGraphQL\Tests\Fixtures\App\Entity\ShopOrder;
use BladL\BestGraphQL\Tests\Fixtures\Services\RandomValuesService;

final readonly class OrderByIdQueryResolver
{
    public function __construct()
    {
    }

    public function resolve(string $id): ShopOrder
    {
        return new ShopOrder($id . '_order');
    }
}
