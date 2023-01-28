<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer\Serializers;

use BladL\BestGraphQL\Serializer\FieldResolverInfo;
use BladL\BestGraphQL\Serializer\SerializerAbstract;
use BladL\BestGraphQL\Serializer\SerializerCollection;
use function is_array;

final readonly class ListSerializer extends SerializerAbstract
{


    public function supports(FieldResolverInfo $info): bool
    {
        return is_array($info->objectValue) && array_is_list($info->objectValue);
    }

    protected function proceedSerialize(FieldResolverInfo $info): mixed
    {
        $value = $info->objectValue;
        \assert(\is_array($value) && \array_is_list($value));
        $newValue = [];
        foreach ($value as $item) {
            $newValue[] = (new SerializerCollection([
                new EnumSerializer($this->getSchemaResolverConfig()),
                new ObjectSerializer($this->getSchemaResolverConfig())
            ]))->serialize(new FieldResolverInfo(objectValue:$item, args: $info->args, contextValue: $info->contextValue, info: $info->info));
        }
        return $newValue;
    }
}
