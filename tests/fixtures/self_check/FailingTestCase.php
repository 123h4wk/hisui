<?php

declare(strict_types=1);

namespace Hisui\Tests\Fixtures\SelfCheck;

use Hisui\Test\TestCase;

final class FailingTestCase extends TestCase
{
    public function testNotEqual(): void
    {
        $this->assertEqual(1, 2);
    }

    public function testNotThrow(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {});
    }

    public function testThrowDifferentClass(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            throw new \RuntimeException("Test");
        });
    }
}
