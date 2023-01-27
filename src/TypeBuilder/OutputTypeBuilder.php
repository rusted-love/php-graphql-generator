<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeBuilder;

use BladL\BestGraphQL\Attributes\Field;
use BladL\BestGraphQL\Attributes\Type;
use BladL\BestGraphQL\Compiler\ClassReflector;
use GraphQL\Type\Definition\ObjectType;
use Nette\PhpGenerator\ClassType;
use function assert;
use function count;
use function is_string;

final readonly class OutputTypeBuilder
{
    private ClassReflector $reflector;

    public function __construct(private string $class)
    {
        $this->reflector = new ClassReflector($this->class);
    }

    public function build()
    {

        $typeAnnotations = $this->reflector->getAttributes(Type::class);
        if (0 === count($typeAnnotations)) {
            return null;
        }

        if (count($typeAnnotations) > 1) {
            throw new \UnexpectedValueException('Class have more than 1 type attribute');
        }

        [$typeAnnotation] = $typeAnnotations;
        $typeName = $typeAnnotation->getArguments()['name'];
        return $this->buildType(name: $typeName);
    }

    private function buildFields(): string
    {
        $methods = $this->reflector->getMethods();
        $str = '';
        foreach ($methods as $method) {
            /**
             * @var \ReflectionAttribute[] $fieldAnnotations
             */
            $fieldAnnotations = $method->getAttributes(Field::class);
            if (0 === \count($fieldAnnotations)) {
                continue;
            }
            foreach ($fieldAnnotations as $annotation) {
                $fieldName = $annotation->getArguments()['name'] ?? $method->getName();
                $description =  $annotation->getArguments()['description'];
                assert(is_string($fieldName));
                $str .= <<<PHP
'$fieldName'=>[
    'type' => Type::listOf(\$userType),
    'description' => '$description',
    'args' => [
        'limit' => [
            'type' => Type::int(),
            'description' => 'Limit the number of recent likes returned',
            'defaultValue' => 10
        ]
    ],
    'resolve' => fn (array \$args): array => DataSource::findLikes(\$blogStory->id, \$args['limit']),
],
PHP;

            }
        }
        return $str;
    }

    private function buildType(string $name): ClassType
    {
        $class = new ClassType('Type' . $name);
        $class->setExtends(ObjectType::class);
        $class->setReadOnly();
        $class->setFinal();
        $method = $class->addMethod('__construct');
        $method->addBody(<<<PHP
\$config = [
    'name'=>'$name',
    'fields' => [
        
    ]
];
parent::__construct(\$config);
PHP
        );
        return $class;
    }
}
