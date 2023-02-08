<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Exception\GraphQLExceptionInterface;
use BladL\BestGraphQL\Tests\TestsHelper;
use BladL\BestGraphQL\Tests\QueryForTesting;

final class ErrorsTest extends EndToEndTestCase
{
    /**
     * @throws GraphQLExceptionInterface
     */
    public function testWrongArgument(): void
    {
        $result = TestsHelper::executeQuery(query: QueryForTesting::WrongArguments, variables: null);
        self::assertEquals('Unknown argument "searchStrings" on field "products" of type "Query". Did you mean "searchString"?', $result->errors[0]->getMessage());
    }

    /**
     * @throws GraphQLExceptionInterface
     */
    public function testWrongField(): void
    {
        $result = TestsHelper::executeQuery(query: QueryForTesting::WrongProductFields, variables: null);
        self::assertEquals('Cannot query field "ide" on type "Product". Did you mean "id"?', $result->errors[0]->getMessage());
    }

    /**
     * @throws GraphQLExceptionInterface
     */
    public function testWrongVariables(): void
    {
        $result = TestsHelper::executeQuery(query: QueryForTesting::ProductSearchString, variables: null);
        self::assertEquals('Variable "$search" of required type "String!" was not provided.', $result->errors[0]->getMessage());
        $result = TestsHelper::executeQuery(query: QueryForTesting::ProductSearchString, variables: [

            'search' => 1
        ]);

        self::assertEquals('Variable "$search" got invalid value 1; String cannot represent a non string value: 1', $result->errors[0]->getMessage());

        $result = TestsHelper::executeQuery(query: QueryForTesting::ProductSearchString, variables: [

            'search' => 'me'
        ]);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('productsSearchResult', $result->data);
        $products = $result->data['productsSearchResult'];
        self::assertIsArray($products);
        self::assertEquals('product_1', $products[0]['id'] ?? '');

    }
}
