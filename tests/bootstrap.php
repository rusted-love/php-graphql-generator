<?php
declare(strict_types=1);
namespace BladL\BestGraphQL\Tests;


use function dirname;

require dirname(__DIR__) . '/vendor/autoload.php';
unlink(Directories::getPathFromRoot(SchemaGeneratorTest::CACHE_FILE_PATH));
