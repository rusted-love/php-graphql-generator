<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Configuration\OperationConfig;
use BladL\BestGraphQL\Configuration\TypesConfig;
use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\Exception\ResolverException;
use BladL\Time\TimeInterval;
use GraphQL\Error\SyntaxError;
use GraphQL\Executor\ExecutionResult;
use Psr\Container\ContainerInterface;

final readonly class GraphQLService
{
    private const TYPES_SUB_NAMESPACE = 'Types\\';
    private const OPERATION_RESOLVER_SUB_NAMESPACE = 'Resolvers\\';
    private SchemaResolverConfig $config;


    public function __construct(
        private string                  $schemaPath,
        private string                  $cacheFilePath,
        string                          $namespace,
        private ContainerInterface      $container,
        private ?TimeInterval           $cacheLifeTime = null,
        private ?SchemaResolverListener $debugResolverListener = null,
    )
    {
        $namespace = Utils::normalizeNamespace($namespace);
        $this->config = new SchemaResolverConfig(typesConfig: new TypesConfig(typeNamespaces: new Namespaces([
            $namespace . self::TYPES_SUB_NAMESPACE
        ])),
            operationConfig: new OperationConfig(resolverNamespaces: new Namespaces([
                $namespace . self::OPERATION_RESOLVER_SUB_NAMESPACE
            ])), container: $this->container
        );
    }

    /**
     * @param string $query
     * @param array<string,mixed>|null $variables
     * @return ExecutionResult
     * @throws ResolverException
     * @throws SyntaxError
     */
    public function executeQuery(string $query, array $variables = null): ExecutionResult
    {
        $factory = new SchemaFactory(schemaPath: $this->schemaPath, cacheFilePath: $this->cacheFilePath, cacheLifetime: $this->cacheLifeTime);
        $schema = $factory->parseSchema();
        $executor = new SchemaExecutor(schema: $schema, schemaResolverConfig: $this->config, resolverListener: $this->debugResolverListener);
        return $executor->executeSchema(queryString: $query, variables: $variables);
    }

    /**
     * @return SchemaResolverConfig
     * @internal
     */
    public function getConfig(): SchemaResolverConfig
    {
        return $this->config;
    }
}
