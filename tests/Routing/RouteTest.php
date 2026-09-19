<?php

declare(strict_types=1);

namespace Hisui\Tests\Routing;

use Hisui\Http\HttpMethod;
use Hisui\Routing\Route;
use Hisui\Routing\RouteAction;
use Hisui\Routing\RouteMatch;
use Hisui\Test\TestCase;

final class RouteTest extends TestCase
{
    private function mockCtrl(): RouteAction
    {
        return new RouteAction('MockController', 'index');
    }

    public function testMatch(): void
    {
        $route = new Route(HttpMethod::Get, '/users/{user_id}', $this->mockCtrl());
        $matched = $route->match(HttpMethod::Get, '/users/100');
        $this->assertSame('100', $matched->params['user_id']);

        $route = new Route(HttpMethod::Post, '/users/{user_id}', $this->mockCtrl());
        $matched = $route->match(HttpMethod::Post, '/users/100/next');
        $this->assertSame(null, $matched);

        $route = new Route(HttpMethod::Get, '/users/{user_id}', $this->mockCtrl());
        $matched = $route->match(HttpMethod::Post, '/users/100');
        $this->assertSame(null, $matched);
    }

    public function testMatchAmbiguousPattern(): void
    {
        $route = new Route(HttpMethod::Get, '/', $this->mockCtrl());
        $matched = $route->match(HttpMethod::Get, '/');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $route = new Route(HttpMethod::Get, 'foo/{param}/hoge/', $this->mockCtrl());
        $matched = $route->match(HttpMethod::Get, '/foo/name/hoge');
        $this->assertSame('name', $matched->params['param']);

        $route = new Route(HttpMethod::Get, '/one/two', $this->mockCtrl());
        $matched = $route->match(HttpMethod::Get, '/one/two/');
        $this->assertSame(true, $matched instanceof RouteMatch);
        $matched = $route->match(HttpMethod::Get, 'one/two');
        $this->assertSame(true, $matched instanceof RouteMatch);
        $matched = $route->match(HttpMethod::Get, 'one/two/three');
        $this->assertSame(null, $matched);
    }

    public function testThrowsErrorWhenParameterDuplicated(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Route(HttpMethod::Get, '/users/{id}/edit/{id}', $this->mockCtrl());
        });
    }
}
