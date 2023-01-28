<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeMapper;

use BladL\BestGraphQL\SchemaResolverConfig;
use BladL\BestGraphQL\TypeMapper\TypeMappers\RootTypeMapper;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

abstract readonly class TypeMapperAbstract implements TypeMapperInterface
{
    /**
     * @param array<string,mixed> $args
     */
    public function __construct(private array $args, private mixed $contextValue, private ResolveInfo $info, private SchemaResolverConfig $schemaResolverConfig)
    {
    }

    /**
     * @return ResolveInfo
     */
    public function getInfo(): ResolveInfo
    {
        return $this->info;
    }


    public function getSchemaResolverConfig(): SchemaResolverConfig
    {
        return $this->schemaResolverConfig;
    }

    protected function makeNewRootMapper(): RootTypeMapper
    {
        return $this->schemaResolverConfig->getRootTypeMapper(contextValue: $this->contextValue, args: $this->args, info: $this->info);
    }

    /**
     * @return array<string,mixed>
     */
    public function getFieldArguments(): array
    {
        return $this->args;
    }

    public function getContextValue(): mixed
    {
        return $this->contextValue;
    }

    protected function getFieldName(): string
    {
        return $this->info->fieldName;

    }

    protected function getParentType(): ObjectType
    {
        return $this->info->parentType;
    }

    /**
     * @template  T of object
     * @param class-string<T> $class
     * @return T
     */
    protected function autoWireClass(string $class): object
    {
        return new $class();
    }

    protected function getNamespace(): string
    {
        return $this->schemaResolverConfig->namespace;
    }
}
