<?php

declare(strict_types=1);

namespace Hisui\Tests\Routing;

use Hisui\Routing\RouteAction;
use Hisui\Routing\RouteMatch;
use Hisui\Test\TestCase;

final class RouteMatchTest extends TestCase
{
    public function testCreate(): void
    {
        $matched = new RouteMatch(
            new RouteAction('MockController', 'index'),
            ['key' => 'value'],
        );
        $this->assertSame('value', $matched->params['key']);
    }
}
