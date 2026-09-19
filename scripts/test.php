<?php

declare(strict_types=1);

use Hisui\Test\TestRunner;
use Hisui\Tests\Test\SelfCheck;
use Hisui\Tests\AutoLoaderTest;
use Hisui\Tests\Http\HttpKernelTest;
use Hisui\Tests\Http\RequestTest;
use Hisui\Tests\Http\ResponseTest;
use Hisui\Tests\Routing\RouteTest;
use Hisui\Tests\Routing\RouterTest;
use Hisui\Tests\Routing\RouteMatchTest;

$autoLoader = require __DIR__ . '/../autoload.php';
$autoLoader->addNamespace('Hisui\\Tests\\', __DIR__ . '/../tests');

SelfCheck::runPassingCase();
SelfCheck::runFailingCase();

$testRunner = new TestRunner();
$testRunner->addTestCase(AutoLoaderTest::class);
$testRunner->addTestCase(HttpKernelTest::class);
$testRunner->addTestCase(RequestTest::class);
$testRunner->addTestCase(ResponseTest::class);
$testRunner->addTestCase(RouteTest::class);
$testRunner->addTestCase(RouterTest::class);
$testRunner->addTestCase(RouteMatchTest::class);
$testResultCollection = $testRunner->run();
$testResultCollection->report();
exit($testResultCollection->getExitCode());
