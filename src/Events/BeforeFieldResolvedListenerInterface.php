<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Events;

use BladL\BestGraphQL\FieldResolver\FieldResolverArguments;

interface BeforeFieldResolvedListenerInterface  extends EventListenerInterface
{
    public function beforeFieldResolve(FieldResolverArguments $info):void;
}
