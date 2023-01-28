<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer;

interface SerializerInterface
{

    public function supports(FieldResolverInfo $info):bool;
    public function serialize(FieldResolverInfo $info):mixed;
}
