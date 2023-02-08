<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Events\EventCollection;
use BladL\BestGraphQL\Events\EventListenerInterface;
use BladL\BestGraphQL\Exception\GraphQLExceptionInterface;
use BladL\BestGraphQL\SchemaCompiler;
use BladL\BestGraphQL\StandardGraphQLServer;
use BladL\BestGraphQL\Tests\Directories;
use BladL\BestGraphQL\Tests\QueryForTesting;
use BladL\Time\TimeInterval;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use UnexpectedValueException;
use function assert;
use function is_array;
use function is_object;

/** @noinspection EfferentObjectCouplingInspection */
abstract class EndToEndTestCase extends TestCase
{
    final protected static function getGraphQLService(): StandardGraphQLServer
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
    final protected static function executeQuery(QueryForTesting $query, ?array $variables, array $listeners = []): ExecutionResult
    {
        $gql = self::getGraphQLService();
        foreach ($listeners as $listener) {
            $gql->getEvents()->add($listener);
        }

        return $gql->executeQuery(query: $query->value, variables: $variables);
    }

    final protected const CACHE_DIR_PATH = '/tests/schema_test_output';
    final protected const SCHEMA_PATH = '/tests/schema_test.graphql';


    final public static function getCache(): AdapterInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return new PhpFilesAdapter(namespace: 'Ssss', directory: Directories::getPathFromRoot(self::CACHE_DIR_PATH), appendOnly: false);
    }

    /**
     * @throws GraphQLExceptionInterface
     */
    final protected static function getSchema(): Schema
    {
        $schemaContent = file_get_contents(Directories::getPathFromRoot(self::SCHEMA_PATH));
        if (false === $schemaContent) {
            throw new UnexpectedValueException('No schema fixture');
        }
        $config = self::getGraphQLService()->getConfig();
        $generator = new SchemaCompiler(schemaPath: $schemaContent, cache: self::getCache(), config: $config);
        return $generator->compileProject()->getSchema();
    }

    /**
     * @param array<int|string,mixed> $array1
     * @param array<int|string,mixed> $array2
     * @return array<int|string,mixed>
     */
    final protected static function checkDiffMulti(array $array1, array $array2): array
    {
        $result = [];
        foreach ($array1 as $key => $val) {
            if (isset($array2[$key])) {
                if (is_array($val) && $array2[$key]) {
                    $array2keyVal = $array2[$key];
                    if (is_array($array2keyVal)) {
                        $subResult = self::checkDiffMulti($val, $array2keyVal);
                        if ($subResult !== []) {
                            $result[$key] = $subResult;
                        }
                    }
                }
                assert(!is_object($val));
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    /**
     * @param array<int|string,mixed> $array1
     * @param array<int|string,mixed> $array2
     * @return void
     */
    final protected static function assertArrayIdentical(array $array1, array $array2): void
    {
        self::assertEquals([], self::checkDiffMulti($array1, $array2));
    }

    /**
     * @param array<int|string,mixed> $expectedResult
     */
    final protected static function assertResultIs(mixed $data, array $expectedResult): void
    {
        self::assertIsArray($data);
        self::assertArrayIdentical($data, $expectedResult);
    }

    protected static function assertExpectedOneError(ExecutionResult $result, string $error): void
    {
        self::assertCount(1, $result->errors);
        self::assertEquals($result->errors[0]->getMessage(), $error);
    }

    /**
     * @param ExecutionResult $result
     * @return array<string,mixed>|null
     */
    protected static function assertExpectNoError(ExecutionResult $result): ?array
    {
        $error = $result->errors[0]??null;
        self::assertNull($error?->getMessage());
        self::assertNotNull($result->data);
        return $result->data;
    }

}
