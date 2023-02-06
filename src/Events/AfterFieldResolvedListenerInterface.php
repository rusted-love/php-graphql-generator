<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Events;

use BladL\BestGraphQL\FieldResolver\FieldResolverResult;

interface AfterFieldResolvedListenerInterface extends EventListenerInterface
{
    public function afterFieldResolved(FieldResolverResult $result):void;
}
