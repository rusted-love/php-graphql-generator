<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Configuration;

use BladL\BestGraphQL\Namespaces;

/**
 * @internal
 */
final  readonly class OperationConfig
{
    public function __construct(
        public Namespaces $resolverNamespaces,
        public string $resolverPrefix = 'Resolver'
    )
    {
    }

    public function getResolverClass(string $operationName, string $fieldName): string|null
    {
        return $this->resolverNamespaces->getRealClass(ucfirst($fieldName) . $operationName  .$this->resolverPrefix);
    }
}
