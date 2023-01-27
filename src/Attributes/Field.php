<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD| Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Field
{
public function __construct(string $name= null)
{
}
}
