<?php

declare(strict_types=1);

namespace Hisui\Tests\Routing;

use Hisui\Http\Method;
use Hisui\Routing\Router;
use Hisui\Routing\RouteMatch;
use Hisui\Test\TestCase;

final class RouterTest extends TestCase
{
    public function testResolve(): void
    {
        $router = new Router();
        $router->get('/users', ['MockController', 'index']);

        $matched = $router->resolve(Method::Get, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Post, '/users');
        $this->assertSame(null, $matched);
    }

    public function testResolveAnyMethod(): void
    {
        $router = new Router();
        $router->get('/users', ['MockController', 'index']);
        $router->post('/users', ['MockController', 'index']);
        $router->put('/users', ['MockController', 'index']);
        $router->patch('/users', ['MockController', 'index']);
        $router->delete('/users', ['MockController', 'index']);
        $router->options('/users', ['MockController', 'index']);
        $router->head('/users', ['MockController', 'index']);

        $matched = $router->resolve(Method::Get, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Post, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Put, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Patch, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Delete, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Options, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Head, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);
    }
}
