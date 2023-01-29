<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use function is_array;

final readonly class Utils
{
    public static function normalizeNamespace(string $namespace):string {
        $namespace = trim($namespace,'\\');
        return "$namespace\\";
    }

    /**
     * @phpstan-assert-if-true array<int,mixed> $value
     */
    public static function valueIsList(mixed $value):bool {
        return is_array($value) && array_is_list($value);
    }
}
