<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class InputType
{
public function __construct(public string $name)
{
}
}
