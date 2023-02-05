<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\App\Entity;

use BladL\BestGraphQL\Tests\Fixtures\App\Objects\Money;

final  readonly  class ShopOrder
{
    public function __construct(private string $id)
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
