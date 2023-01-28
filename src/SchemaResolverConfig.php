<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Serializer\SerializerCollection;
use BladL\BestGraphQL\Serializer\Serializers\EnumSerializer;
use BladL\BestGraphQL\Serializer\Serializers\ListSerializer;
use BladL\BestGraphQL\Serializer\Serializers\ObjectSerializer;
use BladL\BestGraphQL\Serializer\Serializers\OperationSerializer;

final readonly class SchemaResolverConfig
{
    public string $namespace;

    public function __construct(
        string $namespace
    )
    {
        $this->namespace = Normalizer::normalizeNamespace($namespace);
    }

    public function getRootSerializers():SerializerCollection {
        return new SerializerCollection(
            [new EnumSerializer($this),
                new ListSerializer($this),
                new ObjectSerializer($this),
                new OperationSerializer($this)]
        );
    }
}
