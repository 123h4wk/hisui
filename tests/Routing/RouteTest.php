<?php

declare(strict_types=1);

namespace Hisui\Tests\Routing;

use Hisui\Http\HttpMethod;
use Hisui\Routing\Route;
use Hisui\Routing\RouteMatch;
use Hisui\Test\TestCase;

final class RouteTest extends TestCase
{
    public function testMatch(): void
    {
        $callback = function () {};

        $route = new Route(HttpMethod::Get, '/users/{user_id}', $callback);
        $matched = $route->match(HttpMethod::Get, '/users/100');
        $this->assertSame('100', $matched->params['user_id']);

        $route = new Route(HttpMethod::Post, '/users/{user_id}', $callback);
        $matched = $route->match(HttpMethod::Post, '/users/100/next');
        $this->assertSame(null, $matched);

        $route = new Route(HttpMethod::Get, '/users/{user_id}', $callback);
        $matched = $route->match(HttpMethod::Post, '/users/100');
        $this->assertSame(null, $matched);
    }

    public function testMatchAmbiguousPattern(): void
    {
        $callback = function () {};

        $route = new Route(HttpMethod::Get, '/', $callback);
        $matched = $route->match(HttpMethod::Get, '/');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $route = new Route(HttpMethod::Get, 'foo/{param}/hoge/', $callback);
        $matched = $route->match(HttpMethod::Get, '/foo/name/hoge');
        $this->assertSame('name', $matched->params['param']);

        $route = new Route(HttpMethod::Get, '/one/two', $callback);
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
            $callback = function () {};
            new Route(HttpMethod::Get, '/users/{id}/edit/{id}', $callback);
        });
    }
}
