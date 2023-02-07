<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\Events\EventCollection;
use BladL\BestGraphQL\Events\EventListenerInterface;
use BladL\BestGraphQL\Exception\GraphQLExceptionInterface;
use BladL\BestGraphQL\StandardGraphQLServer;
use BladL\BestGraphQL\SchemaCompiler;
use BladL\Time\TimeInterval;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Type\Schema;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use UnexpectedValueException;

final readonly class TestsHelper
{

    public static function getGraphQLService(): StandardGraphQLServer
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
        return new StandardGraphQLServer(schemaPath: Directories::getPathFromRoot(self::SCHEMA_PATH), cache: self::getCache(), namespace: '\BladL\BestGraphQL\Tests\Fixtures\GraphQL', container: $container, eventsContainer: new EventCollection(), cacheLifeTime: TimeInterval::second());

    }

    /**
     * @param array<string,mixed>|null $variables
     * @param EventListenerInterface[] $listeners
     * @throws GraphQLExceptionInterface
     */
    public static function executeQuery(QueryForTesting $query, ?array $variables, array $listeners = []): ExecutionResult
    {
        $gql = self::getGraphQLService();
        foreach ($listeners as $listener) {
            $gql->getEvents()->add($listener);
        }

        return $gql->executeQuery(query: $query->value, variables: $variables);
    }

    public const CACHE_DIR_PATH = '/tests/schema_test_output';
    public const SCHEMA_PATH = '/tests/schema_test.graphql';


    public static function getCache(): AdapterInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return new PhpFilesAdapter(namespace: 'Ssss', directory: Directories::getPathFromRoot(self::CACHE_DIR_PATH), appendOnly: false);
    }

    /**
     * @throws GraphQLExceptionInterface
     */
    public static function getSchema(): Schema
    {
        $schemaContent = file_get_contents(Directories::getPathFromRoot(self::SCHEMA_PATH));
        if (false === $schemaContent) {
            throw new UnexpectedValueException('No schema fixture');
        }
        $config = self::getGraphQLService()->getConfig();
        $generator = new SchemaCompiler(schemaPath: $schemaContent, cache: self::getCache(), config: $config);
        return $generator->compileProject()->getSchema();
    }

}
