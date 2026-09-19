<?php

declare(strict_types=1);

namespace Hisui\Tests\Routing;

use Hisui\Http\HttpMethod;
use Hisui\Routing\Router;
use Hisui\Routing\RouteMatch;
use Hisui\Test\TestCase;

final class RouterTest extends TestCase
{
    public function testResolve(): void
    {
        $callback = function () {};
        $router = new Router();
        $router->get('/users', $callback);

        $matched = $router->resolve(HttpMethod::Get, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Post, '/users');
        $this->assertSame(null, $matched);
    }

    public function testResolveAnyHttpMethod(): void
    {
        $callback = function () {};
        $router = new Router();
        $router->get('/users', $callback);
        $router->post('/users', $callback);
        $router->put('/users', $callback);
        $router->patch('/users', $callback);
        $router->delete('/users', $callback);
        $router->options('/users', $callback);
        $router->head('/users', $callback);

        $matched = $router->resolve(HttpMethod::Get, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Post, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Put, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Patch, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Delete, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Options, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Head, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);
    }
}
