<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Resolvers\ProductsQueryResolver;
use PHPUnit\Framework\TestCase;

final class AutoWiringTest extends TestCase
{
    /**
     * @throws ResolverException
     */
    public function testServiceInstanceIsSame(): void
    {
        $service = ConfigurationFactory::getGraphQLService();
        $resolver1 = $service->getConfig()->getAutoWired(ProductsQueryResolver::class);
        $resolver2 = $service->getConfig()->getAutoWired(ProductsQueryResolver::class);
        self::assertSame($resolver1, $resolver2);
        self::assertInstanceOf(ProductsQueryResolver::class, $resolver1);
        self::assertInstanceOf(ProductsQueryResolver::class, $resolver2);

        self::assertSame($resolver1->service,
            $resolver2->service);
    }
}
