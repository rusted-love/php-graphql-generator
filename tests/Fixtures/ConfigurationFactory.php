<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures;

use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\SchemaExecutor;
use BladL\BestGraphQL\SchemaGenerator;
use BladL\BestGraphQL\SchemaResolverConfig;
use BladL\BestGraphQL\Tests\Directories;
use GraphQL\Error\SyntaxError;
use GraphQL\Type\Schema;
use UnexpectedValueException;

final readonly class ConfigurationFactory
{
    /**
     * @throws SyntaxError
     */
    public static function getSchemaExecutor(?SchemaResolverListener $resolverListener = null): SchemaExecutor
    {
        return new SchemaExecutor(self::getSchema(), schemaResolverConfig: self::getSchemaResolverConfig(), resolverListener: $resolverListener);

    }

    public const CACHE_FILE_PATH = '/tests/schema_test_output.php';
    public const SCHEMA_PATH = '/tests/schema_test.graphql';

    /**
     * @throws SyntaxError
     */
    public static function getSchema(): Schema
    {
        $schemaContent = file_get_contents(Directories::getPathFromRoot(self::SCHEMA_PATH));
        if (false === $schemaContent) {
            throw new UnexpectedValueException('No schema fixture');
        }
        $generator = new SchemaGenerator(schema: $schemaContent, cacheFilePath: Directories::getPathFromRoot(self::CACHE_FILE_PATH));
        return $generator->parseSchema();
    }

    public static function getSchemaResolverConfig(): SchemaResolverConfig
    {
        return new SchemaResolverConfig(namespace: '\BladL\BestGraphQL\Tests\Fixtures\GraphQL');
    }

}
