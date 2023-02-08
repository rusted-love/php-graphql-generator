<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Exception\GraphQLExceptionInterface;
use BladL\BestGraphQL\Tests\Fixtures\App\GraphQLExtension\SecurityEventHookExample;
use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\RoleEnum;
use BladL\BestGraphQL\Tests\QueryForTesting;
use BladL\BestGraphQL\Tests\TestsHelper;
use GraphQL\Executor\ExecutionResult;

final class AccessSecurityMiddlewareExampleTest extends EndToEndTestCase
{
    /**
     * @param QueryForTesting $query
     * @param array<string,mixed> $variables
     * @param RoleEnum[] $currentRoles
     * @return ExecutionResult
     * @throws GraphQLExceptionInterface
     */
    private static function executeRolesQuery(QueryForTesting $query, array $variables = [], array $currentRoles = []): ExecutionResult
    {
        return TestsHelper::executeQuery(query: $query, variables: $variables, listeners: [new SecurityEventHookExample(currentRoles: $currentRoles)]);
    }


    /**
     * @throws GraphQLExceptionInterface
     */
    public function testDirectives(): void
    {
        self::assertExpectedOneError(self::executeRolesQuery(query: QueryForTesting::TestFieldAccess,), error: 'Access denied. Role Manager required!');
        self::assertExpectedOneError(self::executeRolesQuery(query: QueryForTesting::TestFieldAccess, currentRoles: [RoleEnum::Manager]), error: 'Access denied. Role Developer required!',);
        self::assertExpectedOneError(self::executeRolesQuery(query: QueryForTesting::TestFieldAccess, currentRoles: [RoleEnum::Manager, RoleEnum::Developer]), error: 'Access denied. Role Admin required!',);
        self::assertExpectedOneError(self::executeRolesQuery(query: QueryForTesting::TestFieldAccess, currentRoles: [RoleEnum::Admin, RoleEnum::Manager]), error: 'Access denied. Role Developer required!',);

        $data = self::assertExpectNoError(self::executeRolesQuery(query: QueryForTesting::TestFieldAccess, currentRoles: [RoleEnum::Developer, RoleEnum::Admin, RoleEnum::Manager]),);
        self::assertResultIs($data, [
            'typeAccessTest' => [
                'forDev' => 444,
                'forAdmin' => 5000,
                'testRepeatable' => 3002
            ]
        ]);
    }
}
