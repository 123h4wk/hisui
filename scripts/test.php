<?php

declare(strict_types=1);

$autoLoader = require __DIR__ . '/../autoload.php';
$autoLoader->addNamespace('Hisui\\Tests\\', __DIR__ . '/../tests');

use Hisui\Test\TestRunner;
use Hisui\Tests\Test\SelfCheck;
use Hisui\Tests\AutoLoaderTest;

SelfCheck::run();

$testRunner = new TestRunner();
$testRunner->addTestCase(AutoLoaderTest::class);
$testResultCollection = $testRunner->run();
$testResultCollection->report();
exit($testResultCollection->getExitCode());
