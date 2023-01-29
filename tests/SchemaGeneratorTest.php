<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\SchemaFactory;
use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use GraphQL\Error\SyntaxError;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class SchemaGeneratorTest extends TestCase
{


    /**
     * @throws SyntaxError
     */
    public function testSchemaParserValidity(): void
    {
        $schema = ConfigurationFactory::getSchema();
        $schema->assertValid();
        $roleType = $schema->getType('Role');
        self::assertNotNull($roleType);
        self::assertEquals('Role', $roleType->name());
        $userType = $schema->getType('User');
        self::assertNotNull($userType);
        self::assertEquals('User',$userType->name());
    }
}
