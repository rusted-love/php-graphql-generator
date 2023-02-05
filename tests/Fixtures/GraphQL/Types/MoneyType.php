<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

use BladL\BestGraphQL\Attributes\ExternalType;
use BladL\BestGraphQL\Tests\Fixtures\App\Objects\Money;

#[ExternalType(class: Money::class)]
final class MoneyType
{
    public function currency(Money $money): string
    {
        return $money->currency;
    }

    public function amount(Money $money): int
    {
        return $money->amount;
    }
}
