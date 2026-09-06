<?php

declare(strict_types=1);

namespace Hisui\Tests\Fixtures\SelfCheck;

use Hisui\Test\TestCase;

final class PassingTestCase extends TestCase
{
    public function testEqual(): void
    {
        $this->assertEqual(1, 1);
    }

    public function testThrow(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            throw new \InvalidArgumentException("Test");
        });
    }

    public function testThrowSubClass(): void
    {
        $this->assertThrows(\LogicException::class, function () {
            throw new \InvalidArgumentException("Test");
        });
    }
}
