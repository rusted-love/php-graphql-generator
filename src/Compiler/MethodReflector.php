<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Compiler;

final readonly class MethodReflector
{
    public function __construct(private \ReflectionMethod $method)
    {
    }
    public function getAttributes(string $class):array {
        return $this->method->getAttributes();
    }

}
