<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Reflection;

use BladL\BestGraphQL\Exception\ReflectionException;
use function count;

/**
 * @template T of object
 */
final readonly class ReflectionClass
{
    /**
     * @var \ReflectionClass<T> $reflection
     */
    private \ReflectionClass $reflection;

    /**
     * @param class-string<T> $class
     * @throws \ReflectionException
     */
    public function __construct(private string $class)
    {
        $this->reflection = new \ReflectionClass($this->class);
    }

    public function getClassName(): string
    {
        return $this->reflection->getName();
    }

    /**
     * @template M of object
     * @param class-string<M> $attributeClass
     * @return \ReflectionAttribute<M>|null
     * @throws ReflectionException
     */
    public function expectOneOrNoAttribute(string $attributeClass): ?\ReflectionAttribute
    {
        $attributes = $this->reflection->getAttributes($attributeClass);
        if (count($attributes) > 1) {
            throw throw new ReflectionException('More than one attribute ' . $attributeClass . ' in class ' . $this->getClassName());
        }
        return $attributes[0] ?? null;
    }
}
