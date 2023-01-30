<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

use BladL\BestGraphQL\Tests\Fixtures\Services\ProductVariantService;

final readonly class ProductType
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function author(): UserType
    {
        return new UserType();
    }

    /**
     * @return array<int,ProductVariantType>
     */
    public function variants(ProductVariantService $productVariantService,bool $available = true, ): array
    {
        return $productVariantService->findByProduct(productType: $this, available: $available);
    }
    /**
     * @return array<int,ProductVariantType>
     */
    public function wrongVariantReturnValue(ProductVariantService $productVariantService,bool $available = true, ): array
    {
        return $productVariantService->findByProduct(productType: $this, available: $available);
    }
}
