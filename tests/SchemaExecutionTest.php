<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\SchemaExecutor;
use BladL\BestGraphQL\SchemaResolverConfig;

final class SchemaExecutionTest extends \PHPUnit\Framework\TestCase
{
    public function test1(): void
    {
        $query = <<<GRAPHQL
query test1 {
    products(searchString:"best shoes") {
        id
        author {
           roles
        }
    }
}
GRAPHQL;
        $executor = new SchemaExecutor(SchemaGeneratorTest::getSchema(), schemaResolverConfig: new SchemaResolverConfig(namespace: '\BladL\BestGraphQL\Tests\Fixtures\GraphQL'));
        $result = $executor->executeSchema(queryString: $query, variables: null);
        var_dump($result->errors[0]->getMessage());
    }
}
