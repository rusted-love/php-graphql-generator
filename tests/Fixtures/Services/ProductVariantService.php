<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\Services;

use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\ProductType;
use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\ProductVariantType;

final class ProductVariantService
{
    /**
     * @return array<int,ProductVariantType>
     * @noinspection PhpUnusedParameterInspection
     */
    public function findByProduct(ProductType $productType, bool $available = null):array {
        $availableVariants = [new ProductVariantType('available_1'),new ProductVariantType('available_2')];
        $notAvailableVariants= [new ProductVariantType('not_available')];
        return match ($available) {
            true=>$availableVariants,
            false=>$notAvailableVariants,
            null=>[...$availableVariants,...$notAvailableVariants]
        };
    }
}
