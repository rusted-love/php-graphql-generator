<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Debugger\SchemaResolverListener;
use BladL\BestGraphQL\FieldResolver\FieldResolverInfo;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema;
/**
 * @internal
 */
final readonly class SchemaExecutor
{
    public function __construct(private Schema                  $schema, private SchemaResolverConfig $schemaResolverConfig,
                                private ?SchemaResolverListener $resolverListener = null)
    {
    }

    /**
     * @param null|array<string,mixed> $variables
     */
    public function executeSchema(string $queryString, ?array $variables): ExecutionResult
    {
        $config = $this->schemaResolverConfig;
        $resolverListener = $this->resolverListener;
        /**
         * @param array<string,mixed> $args
         */
        $resolver = static function (mixed $objectValue, array $args, mixed $contextValue, ResolveInfo $info) use ($config, $resolverListener): mixed {

            $resolverInput = new FieldResolverInfo(
                objectValue: $objectValue, args: $args, contextValue: $contextValue, info: $info
            );
            $result = $config->getRootSerializers()->serialize($resolverInput);
            $resolverListener?->onSerialized($result);
            return $result->resultValue;
        };
        return GraphQL::executeQuery(
            schema: $this->schema, source: $queryString, variableValues: $variables, fieldResolver: $resolver
        );
    }


}
