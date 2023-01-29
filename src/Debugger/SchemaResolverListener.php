<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Debugger;

use BladL\BestGraphQL\FieldResolver\FieldResolverResult;

interface SchemaResolverListener
{

    public function onSerialized(FieldResolverResult $result):void ;
}
