<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\TypeMapper\TypeMappers;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\TypeMapper\TypeMapperAbstract;
use function assert;

final readonly class RootTypeMapper extends TypeMapperAbstract
{
    public const DEFAULT_MAPPERS_ORDER = [
        OperationTypeMapper::class,
        ListTypeMapper::class,
        EnumTypeMapper::class,
        ObjectTypeMapper::class
    ];

    /**
     * @throws ResolverException
     */
    public function toOutputType(mixed $value): mixed
    {
        $mappersOrder = self::DEFAULT_MAPPERS_ORDER;
        $args = $this->getFieldArguments();
        $resolveInfo = $this->getInfo();
        $contextValue = $this->getContextValue();
        $resolverConfig = $this->getSchemaResolverConfig();

        $mappers = array_map(static function (string $class) use ($resolverConfig, $resolveInfo, $args, $contextValue) {
            assert(class_exists($class));
            assert(is_subclass_of($class, TypeMapperAbstract::class));
            return match ($class) {
                OperationTypeMapper::class => new OperationTypeMapper(args: $args,
                    contextValue: $contextValue, info: $resolveInfo, schemaResolverConfig: $resolverConfig),
                ListTypeMapper::class => new ListTypeMapper(args: $args,
                    contextValue: $contextValue, info: $resolveInfo, schemaResolverConfig: $resolverConfig),
                EnumTypeMapper::class => new EnumTypeMapper(
                    args: $args,
                    contextValue: $contextValue, info: $resolveInfo, schemaResolverConfig: $resolverConfig
                ),
                ObjectTypeMapper::class => new ObjectTypeMapper(args: $args,
                    contextValue: $contextValue, info: $resolveInfo, schemaResolverConfig: $resolverConfig)
            };
        }, $mappersOrder);
        foreach ($mappers as $mapper) {
            assert($mapper instanceof TypeMapperAbstract);
            $value = $mapper->toOutputType($value);
        }
        return $value;
    }


}
