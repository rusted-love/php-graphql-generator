<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use PHPUnit\Framework\TestCase;

final class SchemaExecutionTest extends TestCase
{
public const TEST_QUERY1 = <<<GRAPHQL
query test1 {
    products(searchString:"best shoes") {
        id
        author {
           roles
        }
    }
}
GRAPHQL;
    public function test1(): void
    {
        $result = ConfigurationFactory::getSchemaExecutor()->executeSchema(queryString: self::TEST_QUERY1, variables: null);
        var_dump($result->errors[0]->getMessage());
    }
}
