<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Exception\ClientSafeException;
use BladL\BestGraphQL\Exception\ClientUnsafeException;
use BladL\BestGraphQL\FieldResolver\FieldResolverArguments;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
/**
 * @internal
 */
final readonly class SchemaExecutor
{
    public function __construct(private CompiledProject                  $project, private SchemaResolverConfig $schemaResolverConfig,
    )
    {
    }

    /**
     * @param null|array<string,mixed> $variables
     */
    public function executeSchema(string $queryString, ?array $variables): ExecutionResult
    {
        $config = $this->schemaResolverConfig;
        $project = $this->project;
        /**
         * @param array<string,mixed> $args
         */
        $resolver = static function (mixed $objectValue, array $args, mixed $contextValue, ResolveInfo $info) use ($config,$project): mixed {

            $resolverInput = new FieldResolverArguments(
                objectValue: $objectValue, args: $args, contextValue: $contextValue, info: $info
            );
            $config->events->executeBeforeFieldListener($resolverInput);
            $result = $config->getRootSerializers($project)->resolveField($resolverInput);
            $config->events->executeAfterFieldListener($result);
            return $result->resultValue;
        };
        return GraphQL::executeQuery(
            schema: $this->project->getSchema(), source: $queryString, variableValues: $variables, fieldResolver: $resolver
        );
    }


}
