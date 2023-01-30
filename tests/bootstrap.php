<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;


use function dirname;

require dirname(__DIR__) . '/vendor/autoload.php';
$file = Directories::getPathFromRoot(TestsHelper::CACHE_FILE_PATH);
if (file_exists($file)) {
    unlink($file);

}
