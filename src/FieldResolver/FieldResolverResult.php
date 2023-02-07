<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;

final readonly class FieldResolverResult
{
    public function __construct(
        public mixed                   $resultValue,
        public ?FieldResolverInterface $usedSerializer,
        public FieldResolverArguments $resolverInfo
    )
    {
    }
}
