<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\TypeMapper\TypeMappers\RootTypeMapper;
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
        /**
         * @param array<string,mixed> $args
         */
        $resolver = function (mixed $objectValue, array $args, mixed $contextValue, ResolveInfo $info): mixed {
            $rootMapper = new RootTypeMapper(args: $args, contextValue: $contextValue, info: $info, schemaResolverConfig: $this->schemaResolverConfig);
            return $rootMapper->toOutputType($objectValue);
        };
        return GraphQL::executeQuery(
            schema: $this->schema, source: $queryString, variableValues: $variables, fieldResolver: $resolver
        );
    }


}
