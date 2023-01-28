<?php

namespace BladL\BestGraphQL\Tests\SerializerTests;

use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\Serializer\SerializerCollectionSerializeResult;

final class SimpleResolverListener implements SchemaResolverListener
{
    /**
     * @var SerializerCollectionSerializeResult[]
     */
    public array $log = [];
    public function __construct()
    {
    }

    public function onSerialized(SerializerCollectionSerializeResult $result): void
    {
        $this->log[] = $result;
    }
}
