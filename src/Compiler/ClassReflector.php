<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Compiler;

use BladL\BestGraphQL\Attributes\Type;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

/**
 * @internal
 */
final readonly class ClassReflector
{
    private ReflectionClass $reflectionClass;
    public function __construct(private string $className)
    {
        try {
            $this->reflectionClass = new ReflectionClass($this->className);
        } catch (ReflectionException $e) {
            throw new UnexpectedValueException($e->getMessage(), previous: $e);
        }
    }

    /**
     * @return ReflectionAttribute[]
     */
    public function getAttributes(string $attributeClass): array
    {
        return $this->reflectionClass->getAttributes($attributeClass);
    }

    public function getMethods():\ReflectionMethod {
        return $this->reflectionClass->getMethods();
    }
}
