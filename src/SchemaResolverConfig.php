<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\FieldResolver\FieldResolverCollection;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\TypeObjectFieldResolver;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\OperationFieldResolver;

final readonly class SchemaResolverConfig
{
    public string $namespace;
    private const TYPE_PREFIX = 'Type';

    public function __construct(
        string $namespace
    )
    {
        $this->namespace = Utils::normalizeNamespace($namespace);
    }

    public function getRootSerializers(): FieldResolverCollection
    {
        return new FieldResolverCollection(
               [ new TypeObjectFieldResolver($this),
                new OperationFieldResolver($this)]
        );
    }

    private function getTypesNamespace(): string
    {
        return $this->namespace . 'Types\\';
    }

    public function classIsType(string $class): bool
    {
        return str_starts_with($class, $this->getTypesNamespace()) && str_ends_with($class,self::TYPE_PREFIX);
    }

    public function getTypeClassByName(string $name): string|null
    {
        $class = $this->getTypesNamespace() . $name . 'Type';
        if (class_exists($class)) {
            return $class;
        }
        return null;
    }
}
