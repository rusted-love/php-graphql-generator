<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Serializer\FieldResolverInfo;
use BladL\BestGraphQL\Serializer\SerializerCollection;
use BladL\BestGraphQL\Serializer\Serializers\EnumSerializer;
use BladL\BestGraphQL\Serializer\Serializers\ListSerializer;
use BladL\BestGraphQL\Serializer\Serializers\ObjectSerializer;
use BladL\BestGraphQL\Serializer\Serializers\OperationSerializer;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema;

final readonly class SchemaExecutor
{
    public function __construct(private Schema $schema, private SchemaResolverConfig $schemaResolverConfig)
    {
    }

    /**
     * @param null|array<string,mixed> $variables
     */
    public function executeSchema(string $queryString, ?array $variables): ExecutionResult
    {
        $config = $this->schemaResolverConfig;
        /**
         * @param array<string,mixed> $args
         */
        $resolver = static function (mixed $objectValue, array $args, mixed $contextValue, ResolveInfo $info) use ($config): mixed {

            $resolverInput = new FieldResolverInfo(
                objectValue: $objectValue, args: $args, contextValue: $contextValue, info: $info
            );
            return $config->getRootSerializers()->serialize($resolverInput);
        };
        return GraphQL::executeQuery(
            schema: $this->schema, source: $queryString, variableValues: $variables, fieldResolver: $resolver
        );
    }


}
