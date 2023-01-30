<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

final readonly class ProductVariantType
{
    public function __construct(private string $id,private mixed $accompanyingValue = null)
    {
    }

    public function id(): string
    {
        return 'variant_' . $this->id;
    }

    public function getAccompanyingValue(): mixed
    {
        return $this->accompanyingValue;
    }


}
