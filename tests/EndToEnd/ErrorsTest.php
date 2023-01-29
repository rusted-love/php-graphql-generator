<?php

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use BladL\BestGraphQL\Tests\Queries\QueryExample;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;

final class ErrorsTest extends TestCase
{
    public function testWrongArgument(): void
    {
        $result = ConfigurationFactory::executeQuery(query: QueryExample::WrongArguments->value, variables: null);
        self::assertEquals('Unknown argument "searchStrings" on field "products" of type "Query". Did you mean "searchString"?', $result->errors[0]->getMessage());
    }

    public function testWrongField(): void
    {
        $result = ConfigurationFactory::executeQuery(query: QueryExample::WrongProductFields->value, variables: null);
        self::assertEquals('Cannot query field "ide" on type "Product". Did you mean "id"?', $result->errors[0]->getMessage());
    }

    public function testWrongVariables(): void
    {
        $result = ConfigurationFactory::executeQuery(query: QueryExample::ProductSearchString->value, variables: null);
        self::assertEquals('Variable "$search" of required type "String!" was not provided.', $result->errors[0]->getMessage());
        $result = ConfigurationFactory::executeQuery(query: QueryExample::ProductSearchString->value, variables: [

            'search' => 1
        ]);

        self::assertEquals('Variable "$search" got invalid value 1; String cannot represent a non string value: 1', $result->errors[0]->getMessage());

        $result = ConfigurationFactory::executeQuery(query: QueryExample::ProductSearchString->value, variables: [

            'search' => 'me'
        ]);
        self::assertIsArray($result->data);
        self::assertArrayHasKey('products', $result->data);
        $products = $result->data['products'];
        self::assertIsArray($products);
        self::assertEquals('product_1', $products[0]['id'] ?? '');

    }
}
