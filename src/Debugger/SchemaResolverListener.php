<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Debugger;

use BladL\BestGraphQL\Serializer\SerializerCollectionSerializeResult;

interface SchemaResolverListener
{

    public function onSerialized(SerializerCollectionSerializeResult $result):void ;
}
