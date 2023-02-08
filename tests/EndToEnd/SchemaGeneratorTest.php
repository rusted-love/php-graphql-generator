<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Exception\GraphQLExceptionInterface;
use BladL\BestGraphQL\Tests\TestsHelper;

final class SchemaGeneratorTest extends EndToEndTestCase
{


    /**
     * @throws GraphQLExceptionInterface
     */
    public function testSchemaParserValidity(): void
    {
        $schema = TestsHelper::getSchema();
        $schema->assertValid();
        $roleType = $schema->getType('Role');
        self::assertNotNull($roleType);
        self::assertEquals('Role', $roleType->name());
        $userType = $schema->getType('User');
        self::assertNotNull($userType);
        self::assertEquals('User',$userType->name());
    }
}
