<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\EndToEnd;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\Tests\TestsHelper;
use BladL\BestGraphQL\Tests\QueryForTesting;
use GraphQL\Error\SyntaxError;
use PHPUnit\Framework\TestCase;

final class SchemaExecutionTest extends TestCase
{

    /**
     * @throws SyntaxError
     * @throws ResolverException
     */
    public function testProductAuthorRolesResult(): void
    {
        $result = TestsHelper::executeQuery(query: QueryForTesting::BasicProducts, variables: null);
        self::assertCount(0, $result->errors);
        self::assertNotNull($result->data,'No data returned');
        $products = $result->data['products']??null;
        self::assertNotNull($products,'Bad result');
        self::assertIsArray($products,'Products should be list');
        self::assertArrayHasKey(0,$products,'No products returned');
        $product = $products[0];
        self::assertEquals('product_1', $product['id'],'Product id incorrect');
        self::assertArrayHasKey('author', $product,'Not product author');
        $author = $product['author'];
        self::assertArrayHasKey('roles',$author,'No author role field');
        $roles = $author['roles'];
        self::assertIsArray($roles,'roles field should be list of strings');
        self::assertEquals('Admin',$roles[0]??null);
    }

}
