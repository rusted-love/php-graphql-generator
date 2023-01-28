<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\SchemaGenerator;
use GraphQL\Error\SyntaxError;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class SchemaGeneratorTest extends TestCase
{
    public const CACHE_FILE_PATH = '/tests/schema-output-cache.php';
    public const SCHEMA_PATH = '/tests/schema.test.graphql';

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

    /**
     * @throws SyntaxError
     */
    public function testSchemaParser(): void
    {
        $schema = self::getSchema();
        $schema->assertValid();
        self::assertEquals('RoleType', $schema->getType('RoleType')?->name());
    }
}
