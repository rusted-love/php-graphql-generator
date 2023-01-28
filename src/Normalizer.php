<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

final readonly class Normalizer
{
    public static function normalizeNamespace(string $namespace):string {
        $namespace = trim($namespace,'\\');
        return "$namespace\\";
    }
}
