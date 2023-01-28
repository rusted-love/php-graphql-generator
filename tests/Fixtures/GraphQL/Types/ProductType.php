<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

final readonly class ProductType
{
    public function id(): string
    {
        return 'product_1';
    }

    public function author(): UserType
    {
        return new UserType();
    }
}
