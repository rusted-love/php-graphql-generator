<?php
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\SchemaResolverConfig;
use function array_is_list;
use function gettype;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_object;
use function is_string;

abstract readonly class FieldResolverAbstract implements FieldResolverInterface
{
    public function __construct(protected SchemaResolverConfig $schemaResolverConfig)
    {
    }
    /**
     * @template  T of object
     * @param class-string<T> $class
     * @return T
     */
    protected function autoWireClass(string $class): object
    {
        return new $class();
    }

    abstract protected function proceedSerialize(FieldResolverInfo $info): mixed;

    /**
     * @throws ResolverException
     */
    public function serialize(FieldResolverInfo $info): mixed
    {
        if (!$this->supports($info)) {
            throw new ResolverException('Field not supported for type mapper ' . static::class);
        }
        $value = $this->proceedSerialize($info);
        if (!$this->isFinalValue($value)) {
            throw new ResolverException('Result of type ' . gettype($value) . ' from serializer ' . static::class . ' is not final. Field ' . $info->getFieldName());
        }
        return $value;
    }

    /**
     * @param mixed[] $value
     * @throws ResolverException
     */
    private function isArrayFinalValue(array $value): bool
    {
        if (!array_is_list($value)) {
            throw new ResolverException('Only list array value aeupported ');
        }
        foreach ($value as $item) {
            if (!$this->isFinalValue($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws ResolverException
     */
    private function isFinalValue(mixed $value): bool
    {
        if (is_array($value)) {

            return $this->isArrayFinalValue($value);
        }
        return is_int($value) || is_float($value) || is_string($value) || is_bool($value) || (is_object($value) && $this->schemaResolverConfig->typesConfig->classIsType($value::class));
    }


}
