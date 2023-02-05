<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

use BladL\BestGraphQL\Attributes\ExternalType;
use BladL\BestGraphQL\Tests\Fixtures\App\Entity\ShopOrder;
use BladL\BestGraphQL\Tests\Fixtures\App\Objects\Money;
use BladL\BestGraphQL\Tests\Fixtures\Services\RandomValuesService;

#[ExternalType(class: ShopOrder::class)]
final class OrderType
{
    public function id(ShopOrder $order):string {
        return $order->getId();
    }
    
    public function totalItemPrice(RandomValuesService $randomValuesService):Money {
        return new Money(currency: 'USD', amount: $randomValuesService->getRandomInt());
    }

    public function description(ShopOrder $order):string {
        return  "Order {$order->getId()} description";
    }

    /**
     * @return ProductType[]
     */
    public function products():array {
        return [new ProductType('order_product_1'),new ProductType('order_product_2')];
    }

    public function random(ShopOrder $order,RandomValuesService $randomValuesService):int {
        return $randomValuesService->getRandomInt();
    }
}
