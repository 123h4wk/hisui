<?php

declare(strict_types=1);

namespace Hisui\Tests\Test;

use Hisui\Test\TestRunner;
use Hisui\Tests\Fixtures\SelfCheck\PassingTestCase;
use Hisui\Tests\Fixtures\SelfCheck\FailingTestCase;

final class SelfCheck
{
    public static function runPassingCase(): void
    {
        require_once __DIR__ . '/../fixtures/self_check/PassingTestCase.php';

        $testRunner = new TestRunner();
        $testRunner->addTestCase(PassingTestCase::class);
        $result = $testRunner->run();

        if ($result->getPassedCount() !== 3) {
            throw new \RuntimeException('成功件数が正しくありません。');
        }
    }

    public static function runFailingCase(): void
    {
        require_once __DIR__ . '/../fixtures/self_check/FailingTestCase.php';

        $testRunner = new TestRunner();
        $testRunner->addTestCase(FailingTestCase::class);
        $result = $testRunner->run();

        if ($result->getFailedCount() !== 3) {
            throw new \RuntimeException('失敗件数が正しくありません。');
        }
    }
}
