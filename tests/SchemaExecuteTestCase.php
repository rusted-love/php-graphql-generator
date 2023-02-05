<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use PHPUnit\Framework\TestCase;
use function is_array;

abstract class SchemaExecuteTestCase extends TestCase
{
    /**
     * @param array<int|string,mixed> $array1
     * @param array<int|string,mixed> $array2
     * @return array<int|string,mixed>
     */
    final public static function checkDiffMulti(array $array1, array $array2): array
    {
        $result = [];
        foreach ($array1 as $key => $val) {
            if (isset($array2[$key])) {
                if (is_array($val) && $array2[$key]) {
                    $array2keyVal =  $array2[$key];
                    if (is_array($array2keyVal) ) {
                        $subResult = self::checkDiffMulti($val, $array2keyVal);
                        if ($subResult !== []) {
                            $result[$key] = $subResult;
                        }
                    }
                }
                \assert(!\is_object($val));
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    /**
     * @param array<int|string,mixed> $array1
     * @param array<int|string,mixed> $array2
     * @return void
     */
    public static function assertArrayIdentical(array $array1, array $array2): void
    {
        self::assertEquals([], self::checkDiffMulti($array1, $array2));
    }
}
