<?php

namespace BladL\BestGraphQL\Tests\SerializerTests;

use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\FieldResolver\FieldResolverResult;

final class SimpleResolverListener implements SchemaResolverListener
{
    /**
     * @var FieldResolverResult[]
     */
    public array $log = [];
    public function __construct()
    {
    }

    public function onSerialized(FieldResolverResult $result): void
    {
        $this->log[] = $result;
    }
}
