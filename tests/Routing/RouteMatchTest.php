<?php

declare(strict_types=1);

namespace Hisui\Tests\Routing;

use Hisui\Routing\RouteMatch;
use Hisui\Test\TestCase;

final class RouteMatchTest extends TestCase
{
    public function testCreate(): void
    {
        $callback = fn () => 100;

        $matched = new RouteMatch($callback, ['key' => 'value']);
        $this->assertSame(100, ($matched->action)());
        $this->assertSame('value', $matched->params['key']);
    }
}
