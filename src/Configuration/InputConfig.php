<?php

namespace BladL\BestGraphQL\Configuration;

use BladL\BestGraphQL\Namespaces;

/**
 * @internal
 */
final readonly class InputConfig
{
    public function __construct(public Namespaces $inputNamespaces, public string $inputPrefix = 'Input')
    {
    }
}
