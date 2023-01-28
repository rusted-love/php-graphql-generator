<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\TypeMapper\TypeMappers\RootTypeMapper;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class SchemaResolverConfig
{
    public string $namespace;
    public function __construct(
         string $namespace
    )
    {
        $this->namespace = Normalizer::normalizeNamespace($namespace);
    }

    /**
     * @param mixed $contextValue
     * @param array<string,mixed> $args
     * @param ResolveInfo $info
     * @return RootTypeMapper
     */
    public function getRootTypeMapper(mixed $contextValue,array $args,ResolveInfo $info):RootTypeMapper {
        return new RootTypeMapper(args: $args, contextValue: $contextValue, info: $info, schemaResolverConfig: $this);
    }
}
