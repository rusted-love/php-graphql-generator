<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\Services;

final class RandomValuesService
{
    public function getRandomInt(): int
    {
        return random_int(0, 32433423);
    }
}
