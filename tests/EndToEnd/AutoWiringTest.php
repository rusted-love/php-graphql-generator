<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Exception\FieldResolverException;
use BladL\BestGraphQL\Tests\TestsHelper;
use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Resolvers\ProductsQueryResolver;
use BladL\BestGraphQL\Tests\QueryForTesting;
use PHPUnit\Framework\TestCase;

final class
AutoWiringTest extends TestCase
{
    /**
     * @throws FieldResolverException
     */
    public function testServiceInstanceIsSame(): void
    {
        $service = TestsHelper::getGraphQLService();
        $resolver1 = $service->getConfig()->getService(ProductsQueryResolver::class);
        $resolver2 = $service->getConfig()->getService(ProductsQueryResolver::class);
        self::assertSame($resolver1, $resolver2);
        self::assertInstanceOf(ProductsQueryResolver::class, $resolver1);
        self::assertInstanceOf(ProductsQueryResolver::class, $resolver2);

        self::assertSame($resolver1->service,
            $resolver2->service);
    }

    public function testMethodAutoWiring(): void
    {
        $result = TestsHelper::executeQuery(query: QueryForTesting::ProductWithVariants, variables: [
            'available' => false
        ]);
        $data = $result->data;
        self::assertCount(0, $result->errors);
        self::assertIsArray($data);
        self::assertArrayHasKey('product', $data);
        $product = $data['product'];
        self::assertIsArray($product);
        self::assertArrayHasKey('variants', $product);
        $variants = $product['variants'];
        self::assertCount(1, $variants);

    }

    public function testInvalidReturnedValue():void {
        $result = TestsHelper::executeQuery(query: QueryForTesting::ProductWithVariantsWrongReturnValue, variables: [
            'available' => false
        ]);
        self::assertCount(1, $result->errors);
        self::assertEquals('Invalid value returned in product->wrongVariantReturnValue->id:ID! received array',$result->errors[0]->getMessage());
    }
}
