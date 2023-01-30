<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\StandardGraphQLServer;
use BladL\BestGraphQL\SchemaFactory;
use BladL\Time\TimeInterval;
use GraphQL\Error\SyntaxError;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Type\Schema;
use Psr\Container\ContainerInterface;
use UnexpectedValueException;

final readonly class TestsHelper
{

    public static function getGraphQLService(SchemaResolverListener $resolverListener = null): StandardGraphQLServer
    {
        $container = new class implements ContainerInterface {

            public function get(string $id): null
            {
                return null;
            }

            public function has(string $id): bool
            {

                return false;
            }
        };
        return new StandardGraphQLServer(schemaPath: Directories::getPathFromRoot(self::SCHEMA_PATH), cacheFilePath: Directories::getPathFromRoot(self::CACHE_FILE_PATH), namespace: '\BladL\BestGraphQL\Tests\Fixtures\GraphQL', container: $container, cacheLifeTime: TimeInterval::second(), debugResolverListener: $resolverListener);

    }

    /**
     * @param array<string,mixed>|null $variables
     */
    public static function executeQuery(QueryForTesting $query, ?array $variables, SchemaResolverListener $resolverListener = null): ExecutionResult
    {
        try {
            return self::getGraphQLService(resolverListener: $resolverListener)->executeQuery(query: $query->value, variables: $variables);
        } catch (ResolverException|SyntaxError $e) {
            throw new UnexpectedValueException($e->getMessage(),previous: $e);
        }
    }

    public const CACHE_FILE_PATH = '/tests/schema_test_output.php';
    public const SCHEMA_PATH = '/tests/schema_test.graphql';

    /**
     * @throws SyntaxError|ResolverException
     */
    public static function getSchema(): Schema
    {
        $schemaContent = file_get_contents(Directories::getPathFromRoot(self::SCHEMA_PATH));
        if (false === $schemaContent) {
            throw new UnexpectedValueException('No schema fixture');
        }
        $generator = new SchemaFactory(schemaPath: $schemaContent, cacheFilePath: Directories::getPathFromRoot(self::CACHE_FILE_PATH));
        return $generator->parseSchema();
    }

}
