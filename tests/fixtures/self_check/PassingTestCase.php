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
}
