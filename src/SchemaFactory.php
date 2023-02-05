<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Attributes\ExternalType;
use BladL\BestGraphQL\Exception\ReflectionException;
use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\Reflection\ReflectionClass;
use BladL\Time\TimeInterval;
use GraphQL\Error\SyntaxError;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\TypeDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Type\Schema;
use GraphQL\Utils\AST;
use GraphQL\Utils\BuildSchema;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use function assert;
use function is_array;

/**
 * @phpstan-import-type SchemaMeta from CompiledProject
 * @internal
 */
final class SchemaFactory
{
    private ?Schema $schema = null;
    public function __construct(private readonly string $schemaPath, private readonly AdapterInterface $cache,private readonly SchemaResolverConfig $config, private readonly ?TimeInterval $cacheLifetime = null)
    {

    }

    /**
     * @throws SyntaxError
     * @throws ReflectionException
     * @throws ResolverException
     */
    public function compileProject():CompiledProject {
        return new CompiledProject(meta: $this->getMeta(), schema: $this->resolveSchema(),config: $this->config);
    }
    /**
     * @return SchemaMeta
     * @throws ReflectionException|SyntaxError
     * @throws ResolverException
     */
    private function getMeta():array
    {
        try {
            $item = $this->cache->getItem('graphql_schema_meta');
        } catch (InvalidArgumentException $e) {
            throw new ResolverException(message: $e->getMessage(), previous: $e);
        }
        if ($item->isHit()) {
            /**
             * @var SchemaMeta $value
             */
            $value =  $item->get();
            assert(is_array($value));
            return $value;
        }
        $schema= $this->resolveSchema();

        /**
         * @var SchemaMeta $meta
         */
        $meta = [
            'externalTypes' => [],
        ];
        foreach ($schema->getTypeMap() as $type) {
            $typeName = $type->name();
            $class = $this->config->typesConfig->getTypeClass($typeName);
            if (null !== $class) {
                assert(class_exists($class));
                $reflection = new ReflectionClass($class);
                $attribute = $reflection->expectOneOrNoAttribute(ExternalType::class);
                if (null !== $attribute) {
                    $args = $attribute->getArguments();
                    $class = $args['class'];
                    if (!class_exists($class)) {
                        throw new ResolverException('Class ' . $class . ' defined in attribute not exists');
                    }
                    $meta['externalTypes'][$class] = [
                        'typeClass' => $reflection->getClassName(),
                        'objectClass' => $class,
                        'typeName' => $typeName
                    ];
                }

            }
        }
        $item->set($meta)->expiresAfter($this->cacheLifetime?->getSeconds());
        $this->cache->save($item);
        return $meta;
    }
    /**
     * @throws SyntaxError
     * @throws ResolverException
     */
    private function resolveSchema(): Schema
    {
        if (null !== $this->schema) {
            return $this->schema;
        }
        try {
            $item = $this->cache->getItem('graphql_schema_');
        } catch (InvalidArgumentException $e) {
            throw new ResolverException($e->getMessage(), previous: $e);
        }
        if ($item->isHit()) {
            $value = $item->get();
            assert(is_array($value));
            $document = AST::fromArray($value); // fromArray() is a lazy operation as well

        } else {
            $schemaPath = $this->schemaPath;
            if (!file_exists($schemaPath)) {
                throw new ResolverException('Specified schema path not exists');
            }
            $schemaContent = file_get_contents($schemaPath);
            assert(false !== $schemaContent);
            $document = Parser::parse($schemaContent);
            $item->set(AST::toArray($document))->expiresAfter($this->cacheLifetime?->getSeconds());
            $this->cache->save($item);

        }
        $typeConfigDecorator = static function (array $typeConfig, Node&TypeDefinitionNode $node, array $b): array {
            return $typeConfig;
        };
        assert($document instanceof DocumentNode);
        return $this->schema = BuildSchema::build($document, $typeConfigDecorator);
    }
}
