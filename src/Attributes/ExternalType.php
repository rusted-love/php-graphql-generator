<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class ExternalType
{
    /**
     * @param class-string $class
     */
    public function __construct(public string $class)
    {
    }
}
