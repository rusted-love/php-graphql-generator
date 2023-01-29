<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Configuration;

use BladL\BestGraphQL\Namespaces;

/**
 * @internal
 */
final readonly class TypesConfig
{
    public function __construct(public Namespaces $typeNamespaces, public string $typePrefix = 'Type')
    {
    }

    public function classIsType(string $class): bool
    {
        return $this->typeNamespaces->isClassInNamespace($class) && str_ends_with($class, $this->typePrefix);
    }

    public function getTypeClass(string $name): string|null
    {
        return $this->typeNamespaces->getRealClass($name . $this->typePrefix);
    }

    public function isTypeClassExists(string $name): bool
    {
        return null !== $this->getTypeClass($name);
    }

}
