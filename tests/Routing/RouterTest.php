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
        $router = new Router();
        $router->get('/users', ['MockController', 'index']);

        $matched = $router->resolve(HttpMethod::Get, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(HttpMethod::Post, '/users');
        $this->assertSame(null, $matched);
    }

    public function testResolveAnyHttpMethod(): void
    {
        $router = new Router();
        $router->get('/users', ['MockController', 'index']);
        $router->post('/users', ['MockController', 'index']);
        $router->put('/users', ['MockController', 'index']);
        $router->patch('/users', ['MockController', 'index']);
        $router->delete('/users', ['MockController', 'index']);
        $router->options('/users', ['MockController', 'index']);
        $router->head('/users', ['MockController', 'index']);

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
