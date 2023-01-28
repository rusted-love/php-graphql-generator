<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests;


use BladL\BestGraphQL\Tests\Fixtures\ConfigurationFactory;
use function dirname;

require dirname(__DIR__) . '/vendor/autoload.php';
$file = Directories::getPathFromRoot(ConfigurationFactory::CACHE_FILE_PATH);
if (file_exists($file)) {
    unlink($file);

}
