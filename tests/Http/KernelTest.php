<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\Kernel;
use Hisui\Http\Request;
use Hisui\Http\Response;
use Hisui\DI\Container;
use Hisui\Routing\Router;
use Hisui\Test\TestCase;

final class StubDependency
{
}

final class StubController
{
    public function __construct(
        private StubDependency $dep,
    ) {
    }

    public function requiredUserId(Request $request, string $userId): Response
    {
        $body = $request->method->value . ':' . $userId;
        return new Response(200, $body);
    }

    public function defaultUserId(Request $request, string $userId = 'default'): Response
    {
        $body = $request->method->value . ':' . $userId;
        return new Response(200, $body);
    }

    public function optionalUserId(Request $request, ?string $userId): Response
    {
        $value = $userId === null ? 'null' : $userId;
        $body = $request->method->value . ':' . $value;
        return new Response(200, $body);
    }
}

final class KernelTest extends TestCase
{
    public function testHandleRequest(): void
    {
        $container = new Container();
        $router = new Router();
        $router->get('/users/{userId}', [StubController::class, 'requiredUserId']);
        $kernel = new Kernel($container, $router);
        $response = $kernel->handle(new Request('GET', '/users/hisui'));

        $this->assertSame(200, $response->status);
        $this->assertSame('GET:hisui', $response->body);
    }

    public function testResolveDefaultValue(): void
    {
        $container = new Container();
        $router = new Router();
        $router->get('/users', [StubController::class, 'defaultUserId']);
        $kernel = new Kernel($container, $router);
        $response = $kernel->handle(new Request('GET', '/users'));

        $this->assertSame(200, $response->status);
        $this->assertSame('GET:default', $response->body);
    }

    public function testResolveNullableValue(): void
    {
        $container = new Container();
        $router = new Router();
        $router->get('/users', [StubController::class, 'optionalUserId']);
        $kernel = new Kernel($container, $router);
        $response = $kernel->handle(new Request('GET', '/users'));

        $this->assertSame(200, $response->status);
        $this->assertSame('GET:null', $response->body);
    }

    public function testThrowsWhenRequiredNonNullArgumentCannotBeResolved(): void
    {
        $this->assertThrows(\TypeError::class, function () {
            $container = new Container();
            $router = new Router();
            $router->get('/users', [StubController::class, 'requiredUserId']);
            $kernel = new Kernel($container, $router);
            $kernel->handle(new Request('GET', '/users'));
        });
    }
}
