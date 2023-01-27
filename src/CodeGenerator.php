<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Generator\SchemaRoot;
use Nette\PhpGenerator\PhpNamespace;
use Nette\Utils\Random;

final readonly class CodeGenerator
{
    private string $hash;
    private string $namespace;

    public function __construct()
    {
        $this->hash = Random::generate(8);
        $this->namespace = 'BladL\BestGraphQL' . $this->hash;
    }


    public function generateRoot():SchemaRoot
    {
        $namespace = new PhpNamespace(name: $this->namespace);
        return new SchemaRoot(namespace: $namespace);
    }
}
