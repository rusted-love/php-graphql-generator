<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use GraphQL\Type\Schema;
use function assert;

/**
 * @phpstan-type ExternalTypeMeta = array{typeClass:string,objectClass:string,typeName:string}
 * @phpstan-type SchemaMeta = array{externalTypes:array<string,ExternalTypeMeta>}
 */
final readonly class CompiledProject
{
    /**
     * @param SchemaMeta $meta
     * @param Schema $schema
     * @param SchemaResolverConfig $config
     */
    public function __construct(private array $meta, private Schema $schema, private SchemaResolverConfig $config)
    {
    }

    /**
     * @return SchemaMeta
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getExternalObjectTypeClassService(object $obj): ?string
    {
        $info = $this->meta['externalTypes'][$obj::class] ?? null;
        if (null === $info) {
            return null;
        }
        assert($obj::class === $info['objectClass']);
        return $info['typeClass'];
    }

    public function isExternalObject(object $obj): bool
    {

        return null !== $this->getExternalObjectTypeClassService($obj);
    }

    /**
     * @return Schema
     */
    public function getSchema(): Schema
    {
        return $this->schema;
    }

    /**
     * @return SchemaResolverConfig
     */
    public function getConfig(): SchemaResolverConfig
    {
        return $this->config;
    }
}
