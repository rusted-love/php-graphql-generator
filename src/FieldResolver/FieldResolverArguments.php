<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;

use GraphQL\Type\Definition\ResolveInfo;
/**
 * @internal
 */
final readonly class FieldResolverArguments
{
    /**
     * @param mixed $objectValue
     * @param array<string,mixed> $args
     * @param mixed $contextValue
     * @param ResolveInfo $info
     */
    public function __construct(
        public mixed $objectValue, public array $args, public mixed $contextValue, public ResolveInfo $info
    )
    {
    }
    public function getParentTypeName():string {
        return $this->info->parentType->name();
    }
    public function getFieldName():string {
        return $this->info->fieldName;
    }
}
