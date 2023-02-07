<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

final readonly class AccessTestType
{
    public function forDev():int {
        return  444;
    }
    public function forAdmin():int {
        return  5000;
    }
    public function testRepeatable():int {
        return 3002;
    }
}
