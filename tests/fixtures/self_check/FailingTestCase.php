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
}
