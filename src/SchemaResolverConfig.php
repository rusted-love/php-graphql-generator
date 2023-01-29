<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Configuration\OperationConfig;
use BladL\BestGraphQL\Configuration\TypesConfig;
use BladL\BestGraphQL\FieldResolver\FieldResolverCollection;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\TypeObjectFieldResolver;
use BladL\BestGraphQL\FieldResolver\FieldResolvers\OperationFieldResolver;

/**
 * @internal
 */
final readonly class SchemaResolverConfig
{
    public function __construct(
        public TypesConfig $typesConfig,
        public OperationConfig $operationConfig
    )
    {
    }

    public function getRootSerializers(): FieldResolverCollection
    {
        return new FieldResolverCollection(
               [ new TypeObjectFieldResolver($this),
                new OperationFieldResolver($this)]
        );
    }

}
