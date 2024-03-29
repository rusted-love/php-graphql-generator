<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Configuration\OperationConfig;
use BladL\BestGraphQL\Configuration\TypesConfig;
use BladL\BestGraphQL\Events\EventCollection;
use BladL\BestGraphQL\Exception\FieldResolverException;
use BladL\BestGraphQL\FieldResolver\FieldResolverCollection;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\ExternalTypeObjectFieldResolver;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\ListFieldResolver;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\TypeObjectFieldResolver;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\OperationFieldResolver;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use function is_object;

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
        ContainerInterface     $container,
        public EventCollection $events
    )
    {
        $finalContainer = new Container();
        $finalContainer->delegate($container);
        $this->reflectionContainer = new ReflectionContainer(cacheResolutions: true);
        $finalContainer->delegate($this->reflectionContainer);
        $this->container = $finalContainer;
    }

    public function getRootSerializers(CompiledProject $project): FieldResolverCollection
    {
        return new FieldResolverCollection(
            [new TypeObjectFieldResolver($project),
                new OperationFieldResolver($project),
                new ListFieldResolver($project),
                new ExternalTypeObjectFieldResolver($project)
            ]
        );
    }


    /**
     * @param class-string<T> $class
     * @return T
     *@throws FieldResolverException
     * @template T of object
     */
    public function getService(string $class): object
    {

        try {
            $obj =  $this->container->get($class);
        } catch (NotFoundExceptionInterface $e) {
            throw new FieldResolverException("Entry $class not found in container", previous: $e);

        } catch (ContainerExceptionInterface $e) {
            throw new FieldResolverException('Failed to inject ' . $class, previous: $e);

        }
        if (!is_object($obj)) {
            throw new FieldResolverException("Container returned non object for $class");
        }
        if (!$obj instanceof $class) {
            throw new FieldResolverException("Container expected to return object of class $class. But returned ".$obj::class. ' instead');
        }
        return $obj;

    }

    /**
     * @param callable $func
     * @param array<string|int,mixed> $args
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
