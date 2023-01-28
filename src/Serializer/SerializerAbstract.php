<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\SchemaResolverConfig;

abstract readonly class SerializerAbstract implements SerializerInterface
{
    public function __construct(private SchemaResolverConfig $schemaResolverConfig)
    {
    }

    protected function getSchemaResolverConfig(): SchemaResolverConfig
    {
        return $this->schemaResolverConfig;
    }

    /**
     * @template  T of object
     * @param class-string<T> $class
     * @return T
     */
    protected function autoWireClass(string $class): object
    {
        return new $class();
    }

    protected function getNamespace(): string
    {
        return $this->schemaResolverConfig->namespace;
    }
    abstract protected function proceedSerialize(FieldResolverInfo $info):mixed;

    /**
     * @throws ResolverException
     */
    public function serialize(FieldResolverInfo $info): mixed
    {
      if (!$this->supports($info)) {
          throw new ResolverException('Field not supported for type mapper '.static::class);
      }
      return $this->proceedSerialize($info);
    }
}
