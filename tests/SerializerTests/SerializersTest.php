<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\SerializerTests;

use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\Serializer\SerializerCollectionSerializeResult;
use BladL\BestGraphQL\Tests\Queries\QueryExample;
use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use BladL\BestGraphQL\Tests\SchemaExecutionTest;
use PHPUnit\Framework\TestCase;

final class SerializersTest extends TestCase
{

    public function testProductFixtureSerializer(): void
    {
        $listener = new SimpleResolverListener();
        ConfigurationFactory::getSchemaExecutor(resolverListener: $listener)->executeSchema(
            queryString: QueryExample::BasicProducts->value, variables: null
        );

        $productLog = null;
        foreach ($listener->log as $item) {
            $info = $item->resolverInfo->info;
            if ('Product' === $info->parentType->name && 'id' === $info->fieldName) {
                $productLog = $item;
                break;
            }
        }
        self::assertNotNull($productLog);
        self::assertEquals('product_1', $productLog->resultValue);
    }
}
