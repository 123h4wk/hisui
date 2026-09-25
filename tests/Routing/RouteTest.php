<?php

declare(strict_types=1);

namespace Hisui\Tests\Routing;

use Hisui\Http\Method;
use Hisui\Routing\Route;
use Hisui\Routing\RouteAction;
use Hisui\Routing\RouteMatch;
use Hisui\Test\TestCase;

final class RouteTest extends TestCase
{
    private function mockCtrl(): RouteAction
    {
        return new RouteAction(StubController::class, 'index');
    }

    public function testMatch(): void
    {
        $route = new Route(Method::Get, '/users/{user_id}', $this->mockCtrl());
        $matched = $route->match(Method::Get, '/users/100');
        $this->assertSame('100', $matched->params['user_id']);

        $route = new Route(Method::Post, '/users/{user_id}', $this->mockCtrl());
        $matched = $route->match(Method::Post, '/users/100/next');
        $this->assertSame(null, $matched);

        $route = new Route(Method::Get, '/users/{user_id}', $this->mockCtrl());
        $matched = $route->match(Method::Post, '/users/100');
        $this->assertSame(null, $matched);
    }

    public function testMatchAmbiguousPattern(): void
    {
        $route = new Route(Method::Get, '/', $this->mockCtrl());
        $matched = $route->match(Method::Get, '/');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $route = new Route(Method::Get, 'foo/{param}/hoge/', $this->mockCtrl());
        $matched = $route->match(Method::Get, '/foo/name/hoge');
        $this->assertSame('name', $matched->params['param']);

        $route = new Route(Method::Get, '/one/two', $this->mockCtrl());
        $matched = $route->match(Method::Get, '/one/two/');
        $this->assertSame(true, $matched instanceof RouteMatch);
        $matched = $route->match(Method::Get, 'one/two');
        $this->assertSame(true, $matched instanceof RouteMatch);
        $matched = $route->match(Method::Get, 'one/two/three');
        $this->assertSame(null, $matched);
    }

    public function testThrowsErrorWhenParameterDuplicated(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Route(Method::Get, '/users/{id}/edit/{id}', $this->mockCtrl());
        });
    }
}
