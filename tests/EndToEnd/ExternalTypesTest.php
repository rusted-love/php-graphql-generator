<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\FieldResolver\FieldResolvers\ExternalTypeObjectFieldResolver;
use BladL\BestGraphQL\Tests\QueryForTesting;
use BladL\BestGraphQL\Tests\SchemaExecuteTestCase;
use BladL\BestGraphQL\Tests\SimpleResolverResultListener;
use BladL\BestGraphQL\Tests\TestsHelper;

final class ExternalTypesTest extends SchemaExecuteTestCase
{
    public function testExpectedBehaviour(): void
    {
        $listener =new SimpleResolverResultListener();
        $result = TestsHelper::executeQuery(QueryForTesting::ExternalType, [],listeners: [$listener]);
        $data = $result->data;
        foreach ($listener->log as $item) {

            if ('Order' === $item->resolverInfo->getParentTypeName()) {
                self::assertInstanceOf(ExternalTypeObjectFieldResolver::class,$item->usedSerializer);
            }
        }
        $arr = [
            'order' =>
                [
                    'id' => 'amazing_order_order',
                    'description' => 'Order amazing_order_order description',
                    'totalItemPrice' =>
                        [
                            'currency' => 'USD',
                            'amount' => 13619009,
                        ],
                    'products' =>
                        [
                            0 =>
                                [
                                    'id' => 'order_product_1',
                                    'author' =>
                                        [
                                            'roles' =>
                                                [
                                                    0 => 'Admin',
                                                ],
                                        ],
                                ],
                            1 =>
                                [
                                    'id' => 'order_product_2',
                                    'author' =>
                                        [
                                            'roles' =>
                                                [
                                                    0 => 'Admin',
                                                ],
                                        ],
                                ],
                        ],
                ],
        ];
        self::assertNotNull($data);
        self::assertArrayIdentical($arr,$data);
    }

}
