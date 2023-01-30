<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\SerializerTests;

use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\FieldResolver\FieldResolverResult;
use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\RoleEnum;
use BladL\BestGraphQL\Tests\Queries\QueryExample;
use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use BladL\BestGraphQL\Tests\SchemaExecutionTest;
use PHPUnit\Framework\TestCase;

final class SerializersTest extends TestCase
{

    public function testProductFixtureSerializer(): void
    {
        $listener = new SimpleResolverListener();
       $result =  ConfigurationFactory::executeQuery(
            query: QueryExample::BasicProducts->value, variables: null,resolverListener:$listener
        );
       self::assertCount(0,$result->errors);

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
        $roleEnumLog = null;
        foreach ($listener->log as $item) {
            if ('[Role!]' === $item->resolverInfo->info->returnType->toString()) {
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
