<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Exception\GraphQLExceptionInterface;
use BladL\BestGraphQL\Tests\Fixtures\App\GraphQLExtension\SecurityEventHookExample;
use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\RoleEnum;
use BladL\BestGraphQL\Tests\QueryForTesting;
use BladL\BestGraphQL\Tests\SchemaExecuteTestCase;
use BladL\BestGraphQL\Tests\TestsHelper;
use GraphQL\Executor\ExecutionResult;
use PHPUnit\Framework\TestCase;

final class AccessSecurityMiddlewareExampleTest extends SchemaExecuteTestCase
{
    /**
     * @param QueryForTesting $query
     * @param array<string,mixed> $variables
     * @param RoleEnum[] $currentRoles
     * @return ExecutionResult
     * @throws GraphQLExceptionInterface
     */
    private static function executeQuery(QueryForTesting $query, array $variables = [], array $currentRoles = []): ExecutionResult
    {
        return TestsHelper::executeQuery(query: $query, variables: $variables, listeners: [new SecurityEventHookExample(currentRoles: $currentRoles)]);
    }

    /**
     * @param QueryForTesting $query
     * @param string $error
     * @param array<string,mixed> $variables
     * @param RoleEnum[] $currentRoles
     * @return void
     */
    private static function assertExpectedError(QueryForTesting $query, string $error, array $variables = [], array $currentRoles = []): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = self::executeQuery(query: $query, variables: $variables, currentRoles: $currentRoles);
        self::assertCount(1, $result->errors);
        self::assertEquals($result->errors[0]->getMessage(), $error);
    }

    /**
     * @param QueryForTesting $query
     * @param array<string,mixed> $variables
     * @param RoleEnum[] $currentRoles
     * @return mixed
     */
    private static function assertExpectNoError(QueryForTesting $query, array $variables = [], array $currentRoles = []): mixed
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = self::executeQuery(query: $query, variables: $variables, currentRoles: $currentRoles);
        $error = $result->errors[0]??null;
        self::assertNull($error?->getMessage());
        self::assertNotNull($result->data);
        return $result->data;
    }

    public function testDirectives(): void
    {
        self::assertExpectedError(query: QueryForTesting::TestFieldAccess, error: 'Access denied. Role Manager required!');
        self::assertExpectedError(query: QueryForTesting::TestFieldAccess, error: 'Access denied. Role Developer required!', currentRoles: [RoleEnum::Manager]);
        self::assertExpectedError(query: QueryForTesting::TestFieldAccess, error: 'Access denied. Role Admin required!', currentRoles: [RoleEnum::Manager, RoleEnum::Developer]);
        self::assertExpectedError(query: QueryForTesting::TestFieldAccess, error: 'Access denied. Role Developer required!', currentRoles: [RoleEnum::Admin, RoleEnum::Manager]);

        $data = self::assertExpectNoError(query: QueryForTesting::TestFieldAccess, currentRoles: [RoleEnum::Developer, RoleEnum::Admin,RoleEnum::Manager]);
        self::assertResultIs($data,[
            'typeAccessTest'=>[
                'forDev'=>444,
                'forAdmin'=>5000,
                'testRepeatable'=>3002
            ]
        ]);
    }
}
