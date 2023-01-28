<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Serializer;

final readonly class SerializerCollectionSerializeResult
{
    public function __construct(
        public mixed               $resultValue,
        public ?SerializerInterface $usedSerializer
    )
    {
    }
}
