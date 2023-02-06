<?php
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver;

use BladL\BestGraphQL\CompiledProject;
use BladL\BestGraphQL\Exception\ResolverException;
use BladL\BestGraphQL\SchemaResolverConfig;
use BladL\BestGraphQL\Tests\Fixtures\App\Entity\ShopOrder;
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
    protected SchemaResolverConfig $schemaResolverConfig;

    public function __construct(protected CompiledProject $project)
    {
        $this->schemaResolverConfig = $project->getConfig();
    }

    abstract protected function proceedResolve(FieldResolverInfo $info): mixed;

    /**
     * @throws ResolverException
     */
    public function resolve(FieldResolverInfo $info): mixed
    {
        if (!$this->supports($info)) {
            throw new ResolverException('Field not supported for type mapper ' . static::class);
        }
        $value = $this->proceedResolve($info);
        if (!$this->isFinalValue($value)) {
            throw new ResolverException('Result of type ' . gettype($value) . ' from serializer ' . static::class . ' is not final. Field ' . $info->getFieldName());
        }
        if (is_array($info->objectValue) && is_array($value)) {
            \assert($this->countdim($info->objectValue) !== $this->countdim($value));//Count of array dimensions after serialization increased
        }

        return $value;
    }

    /**
     * @param mixed[] $array
     * @return int
     */
    private function countdim(array $array): int
    {
        if (is_array(reset($array))) {
            return $this->countdim(reset($array)) + 1;
        }
        return 1;
    }

    /**
     * @param mixed[] $value
     * @throws ResolverException
     */
    private function isArrayFinalValue(array $value): bool
    {
        /*if (!array_is_list($value)) {
            throw new ResolverException('Only list array value aeupported '); Return back if return type of array
        }*/
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
        return is_int($value) || is_float($value) || is_string($value) || is_bool($value) || (is_object($value) && ($this->schemaResolverConfig->typesConfig->classIsType($value::class) || $this->project->isExternalObject($value)));
    }


}
