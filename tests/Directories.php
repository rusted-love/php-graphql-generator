<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;

use function dirname;

final class Directories
{
    public static function getPathFromRoot(string $path): string
    {
        return dirname(__DIR__) . $path;
    }
}
