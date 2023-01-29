<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;
/**
 * @internal
 */
final readonly class Namespaces
{
    /**
     * @var array<int,string>
     */
    private array $namespaces;

    /**
     * @param array<int,string> $namespaces
     */
    public function __construct(array $namespaces)
    {
        $this->namespaces = array_map([Utils::class, 'normalizeNamespace'], $namespaces);
}

    /**
     * @return array<int,string>
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    public function isClassInNamespace(string $class):bool {
        foreach ($this->namespaces as $namespace) {
            if (str_starts_with($class, $namespace)) {
                return true;
            }
        }
        return false;
    }


    public function getRealClass(string $pathFromRoot): string|null
    {
        foreach ($this->namespaces as $namespace) {
            $class = $namespace .$pathFromRoot;
            if (class_exists($class)) {
                return $class;
            }
        }
        return null;
    }
}
