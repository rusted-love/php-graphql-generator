<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;

interface FieldResolverInterface
{

    public function supports(FieldResolverArguments $info):bool;
    public function resolve(FieldResolverArguments $info):mixed;
}
