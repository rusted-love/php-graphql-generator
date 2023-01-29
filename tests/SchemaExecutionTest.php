<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\RoleEnum;
use BladL\BestGraphQL\Tests\Queries\QueryExample;
use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use GraphQL\Error\SyntaxError;
use PHPUnit\Framework\TestCase;

final class SchemaExecutionTest extends TestCase
{

    /**
     * @throws SyntaxError
     */
    public function testProductAuthorRolesResult(): void
    {
        $result = ConfigurationFactory::executeQuery(query: QueryExample::BasicProducts->value, variables: null);

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
