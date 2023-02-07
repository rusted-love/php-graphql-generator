<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Configuration\OperationConfig;
use BladL\BestGraphQL\Configuration\TypesConfig;
use BladL\BestGraphQL\Events\EventCollection;
use BladL\BestGraphQL\Exception\CacheException;
use BladL\BestGraphQL\Exception\CompilerException;
use BladL\BestGraphQL\Exception\FieldResolverException;
use BladL\Time\TimeInterval;
use GraphQL\Error\SyntaxError;
use GraphQL\Executor\ExecutionResult;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

final readonly class StandardGraphQLServer
{
    private const TYPES_SUB_NAMESPACE = 'Types\\';
    private const OPERATION_RESOLVER_SUB_NAMESPACE = 'Resolvers\\';
    private SchemaResolverConfig $config;


    public function __construct(
        private string             $schemaPath,
        private AdapterInterface   $cache,
        string                     $namespace,
        private ContainerInterface $container,
        EventCollection            $eventsContainer,
        private ?TimeInterval      $cacheLifeTime = null,

    )
    {
        $namespace = Utils::normalizeNamespace($namespace);
        $this->config = new SchemaResolverConfig(typesConfig: new TypesConfig(typeNamespaces: new Namespaces([
            $namespace . self::TYPES_SUB_NAMESPACE
        ])), operationConfig: new OperationConfig(resolverNamespaces: new Namespaces([
            $namespace . self::OPERATION_RESOLVER_SUB_NAMESPACE
        ])),
            container: $this->container, events: $eventsContainer
        );
    }

    /**
     * @param string $query
     * @param array<string,mixed>|null $variables
     * @return ExecutionResult
     * @throws CompilerException|CacheException
     */
    public function executeQuery(string $query, array $variables = null): ExecutionResult
    {
        $factory = new SchemaCompiler(schemaPath: $this->schemaPath, cache: $this->cache, config: $this->config, cacheLifetime: $this->cacheLifeTime);
        $project = $factory->compileProject();
        $executor = new SchemaExecutor(project: $project, schemaResolverConfig: $this->config);
        return $executor->executeSchema(queryString: $query, variables: $variables);
    }

    public function getEvents(): EventCollection
    {
        return $this->config->events;
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
