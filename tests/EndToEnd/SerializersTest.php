<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Tests\QueryForTesting;
use BladL\BestGraphQL\Tests\SimpleResolverResultListener;
use BladL\BestGraphQL\Tests\TestsHelper;
use PHPUnit\Framework\TestCase;

final class SerializersTest extends TestCase
{

    public function testProductFixtureSerializer(): void
    {
        $listener = new SimpleResolverResultListener();
       $result =  TestsHelper::executeQuery(
            query: QueryForTesting::BasicProducts, variables: null,listeners:[$listener]
        );
       self::assertCount(0,$result->errors);

        $productLog = null;
        foreach ($listener->log as $item) {
            $info = $item->resolverArguments->info;
            if ('Product' === $info->parentType->name && 'id' === $info->fieldName) {
                $productLog = $item;
                break;
            }
        }
        self::assertNotNull($productLog);
        self::assertEquals('product_1', $productLog->resultValue);
        $roleEnumLog = null;
        foreach ($listener->log as $item) {
            if ('[Role!]' === $item->resolverArguments->info->returnType->toString()) {
                $roleEnumLog = $item;
                break;
            }
        }
        self::assertNotNull($roleEnumLog,'roles was not serialized');
        $resultValue = $roleEnumLog->resultValue;
        self::assertIsArray($resultValue);
        self::assertEquals('Admin',$resultValue[0]??null);
    }
}
