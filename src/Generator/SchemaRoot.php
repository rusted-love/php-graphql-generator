<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Generator;

use BladL\BestGraphQL\TypeBuilder\OutputTypeBuilder;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

readonly class SchemaRoot
{
    private
    public function __construct(private PhpNamespace $namespace)
    {
    }


    public function getNamespace(): PhpNamespace
    {
        return $this->namespace;
    }

    public function addType(OutputTypeBuilder $name):void{
        $class = new ClassType($na);
        $this->namespace->addClass()
    }

}
