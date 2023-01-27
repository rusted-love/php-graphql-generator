<?php

declare(strict_types=1);

use BladL\Tests\Directories;
use BladL\Tests\SchemaGeneratorTest;

require dirname(__DIR__) . '/vendor/autoload.php';
unlink(Directories::getPathFromRoot(SchemaGeneratorTest::CACHE_FILE_PATH));
