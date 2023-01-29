<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;

final readonly class FieldResolverCollection
{
    /**
     * @param FieldResolverInterface[] $serializers
     */
    public function __construct(
        private array $serializers
    )
    {
    }

    public function serialize(FieldResolverInfo $info): FieldResolverResult
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($info)) {
                return new FieldResolverResult(
                    resultValue: $serializer->serialize($info), usedSerializer: $serializer, resolverInfo: $info
                );
            }
        }
        return new FieldResolverResult(
            resultValue: $info->objectValue, usedSerializer: null, resolverInfo: $info
        );
    }
}
