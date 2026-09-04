<?php

declare(strict_types=1);

namespace Hisui\Tests\Test;

use Hisui\Test\TestRunner;
use Hisui\Tests\Fixtures\SelfCheck\PassingTestCase;
use Hisui\Tests\Fixtures\SelfCheck\FailingTestCase;

final class SelfCheck
{
    public static function run(): void
    {
        require_once __DIR__ . '/../fixtures/self_check/PassingTestCase.php';
        require_once __DIR__ . '/../fixtures/self_check/FailingTestCase.php';

        $testRunner = new TestRunner();
        $testRunner->addTestCase(PassingTestCase::class);
        $testRunner->addTestCase(FailingTestCase::class);
        $result = $testRunner->run();

        if ($result->getPassedCount() !== 1) {
            throw new \RuntimeException('成功件数が正しくありません。');
        }

        if ($result->getFailedCount() !== 1) {
            throw new \RuntimeException('失敗件数が正しくありません。');
        }
    }
}
