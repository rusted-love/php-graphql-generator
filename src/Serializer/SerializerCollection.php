<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer;

final readonly class SerializerCollection
{
    /**
     * @param SerializerInterface[] $serializers
     */
    public function __construct(
        private array $serializers
    )
    {
    }

    public function serialize(FieldResolverInfo $info):SerializerCollectionSerializeResult {
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($info)) {
                return  new SerializerCollectionSerializeResult(
                    resultValue:$serializer->serialize($info),usedSerializer: $serializer
                );
            }
        }
        return new SerializerCollectionSerializeResult(
            resultValue: $info->objectValue,usedSerializer: null
        );
    }
}
