<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Configuration\OperationConfig;
use BladL\BestGraphQL\Configuration\TypesConfig;
use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\FieldResolver\FieldResolverCollection;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\ListFieldResolver;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\TypeObjectFieldResolver;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\OperationFieldResolver;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 */
final readonly class SchemaResolverConfig
{
    private ContainerInterface $container;
    private ReflectionContainer $reflectionContainer;

    public function __construct(
        public TypesConfig     $typesConfig,
        public OperationConfig $operationConfig,
        ContainerInterface     $container
    )
    {
        $finalContainer = new Container();
        $finalContainer->delegate($container);
        $this->reflectionContainer = new ReflectionContainer(cacheResolutions: true);
        $finalContainer->delegate($this->reflectionContainer);
        $this->container = $finalContainer;
    }

    public function getRootSerializers(): FieldResolverCollection
    {
        return new FieldResolverCollection(
            [new TypeObjectFieldResolver($this),
                new OperationFieldResolver($this),
                new ListFieldResolver($this)
            ]
        );
    }


    /**
     * @throws ResolverException
     */
    public function getAutoWired(string $class): mixed
    {

        try {
            return $this->container->get($class);
        } catch (NotFoundExceptionInterface $e) {
            throw new ResolverException("Entry $class not found in container", previous: $e);

        } catch (ContainerExceptionInterface $e) {
            throw new ResolverException('Failed to inject ' . $class, previous: $e);

        }
    }

    /**
     * @param callable $func
     * @param array<string,mixed> $args
     * @return mixed
     */
    public function callAutoWired(callable $func, array $args = []): mixed
    {
        return $this->reflectionContainer->call($func, $args);
    }

    public function isClassSchemaService(string $class): bool
    {
        return $this->operationConfig->resolverNamespaces->isClassInNamespace($class);
    }
}
