<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\SchemaGenerator;
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
    public function testSchemaParser(): void
    {
        $schema = ConfigurationFactory::getSchema();
        $schema->assertValid();
        self::assertEquals('RoleType', $schema->getType('RoleType')?->name());
    }
}
