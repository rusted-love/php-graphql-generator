<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Utils\ClassFinder;
use Nette\PhpGenerator\PhpNamespace;
use Psr\Container\ContainerInterface;

final readonly class Compiler
{
    /**
     * @param string[] $namespaces
     */
    public function __construct(
        private array              $namespaces,
        private string             $appRoot,
        private ContainerInterface $container
    )
    {
    }

    /**
     * @return array<int,string>
     */
    private function getAllClasses(): array
    {
        $classFinder = new ClassFinder(appRoot: $this->appRoot);

        $classes = [];
        foreach ($this->namespaces as $namespace) {
            $classes[] = [...$classes, $classFinder->getClassesInNamespace($namespace)];
        }
        return $classes;
    }

    public function compile(): string
    {
        $classes = $this->getAllClasses();

foreach ($classes as $class) {
    $class->get
}
    }

    private function generateType(string $className):{
        $namespace = new PhpNamespace();
        $class = new \Nette\PhpGenerator\ClassType('Demo');

        $class->setFinal()
            ->setExtends(ParentClass::class)
            ->addImplement(Countable::class)
            ->addComment("Description of class.\nSecond line\n")
            ->addComment('@property-read Nette\Forms\Form $form');
    }
}
