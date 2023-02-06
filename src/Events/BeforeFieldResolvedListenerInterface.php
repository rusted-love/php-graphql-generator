<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Events;

use BladL\BestGraphQL\FieldResolver\FieldResolverInfo;

interface BeforeFieldResolvedListenerInterface  extends EventListenerInterface
{
    public function beforeFieldResolve(FieldResolverInfo $info):void;
}
