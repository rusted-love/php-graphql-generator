<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\App\Objects;

final class Money
{
    public function __construct(public string $currency, public int $amount)
    {
    }
}
