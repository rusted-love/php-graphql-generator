<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Configuration\OperationConfig;
use BladL\BestGraphQL\Configuration\TypesConfig;
use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\FieldResolver\FieldResolverCollection;
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

    public function __construct(
        public TypesConfig     $typesConfig,
        public OperationConfig $operationConfig,
        ContainerInterface     $container
    )
    {
        $finalContainer = new Container();
        $finalContainer->delegate($container);
        $finalContainer->delegate((new ReflectionContainer(cacheResolutions: true)));
        $this->container = $finalContainer;
    }

    public function getRootSerializers(): FieldResolverCollection
    {
        return new FieldResolverCollection(
            [new TypeObjectFieldResolver($this),
                new OperationFieldResolver($this)]
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

    public function isClassSchemaService(string $class): bool
    {
        return $this->operationConfig->resolverNamespaces->isClassInNamespace($class);
    }
}
