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
        $router->get('/users', [StubController::class, 'index']);

        $matched = $router->resolve(Method::Get, '/users');
        $this->assertSame(true, $matched instanceof RouteMatch);

        $matched = $router->resolve(Method::Post, '/users');
        $this->assertSame(null, $matched);
    }

    public function testResolveAnyMethod(): void
    {
        $router = new Router();
        $router->get('/users', [StubController::class, 'index']);
        $router->post('/users', [StubController::class, 'index']);
        $router->put('/users', [StubController::class, 'index']);
        $router->patch('/users', [StubController::class, 'index']);
        $router->delete('/users', [StubController::class, 'index']);
        $router->options('/users', [StubController::class, 'index']);
        $router->head('/users', [StubController::class, 'index']);

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
