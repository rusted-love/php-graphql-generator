<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;
/**
 * @internal
 */
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

    public function resolveField(FieldResolverArguments $info): FieldResolverResult
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($info)) {
                return new FieldResolverResult(
                    resultValue: $serializer->resolve($info), usedSerializer: $serializer, resolverInfo: $info
                );
            }
        }
        return new FieldResolverResult(
            resultValue: $info->objectValue, usedSerializer: null, resolverInfo: $info
        );
    }
}
