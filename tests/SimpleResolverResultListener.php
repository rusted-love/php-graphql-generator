<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\Events\AfterFieldResolvedListenerInterface;
use BladL\BestGraphQL\FieldResolver\FieldResolverResult;

final class SimpleResolverResultListener implements AfterFieldResolvedListenerInterface
{
    /**
     * @var FieldResolverResult[]
     */
    public array $log = [];
    public function __construct()
    {
    }

    public function afterFieldResolved(FieldResolverResult $result): void
    {
        $this->log[] = $result;
    }
}
