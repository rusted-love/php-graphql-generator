<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;

interface FieldResolverInterface
{

    public function supports(FieldResolverInfo $info):bool;
    public function serialize(FieldResolverInfo $info):mixed;
}
