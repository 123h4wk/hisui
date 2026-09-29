<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\Kernel;
use Hisui\Http\Request;
use Hisui\Http\Response;
use Hisui\Http\Error\ErrorHandler;
use Hisui\DI\Container;
use Hisui\Routing\Router;
use Hisui\Test\TestCase;

final class StubErrorHandler implements ErrorHandler
{
    public function handle(\Throwable $e, Request $request): Response
    {
        return new Response(500, $e->getMessage());
    }
}

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

    public function error(): Response
    {
        throw new \RuntimeException('TestException');
    }
}

final class KernelTest extends TestCase
{
    public function testHandleRequest(): void
    {
        $container = new Container();
        $router = new Router();
        $errorHandler = new StubErrorHandler();
        $router->get('/users/{userId}', [StubController::class, 'requiredUserId']);
        $kernel = new Kernel($container, $router, $errorHandler);
        $response = $kernel->handle(new Request('GET', '/users/hisui'));

        $this->assertSame(200, $response->status);
        $this->assertSame('GET:hisui', $response->body);
    }

    public function testResolveDefaultValue(): void
    {
        $container = new Container();
        $router = new Router();
        $errorHandler = new StubErrorHandler();
        $router->get('/users', [StubController::class, 'defaultUserId']);
        $kernel = new Kernel($container, $router, $errorHandler);
        $response = $kernel->handle(new Request('GET', '/users'));

        $this->assertSame(200, $response->status);
        $this->assertSame('GET:default', $response->body);
    }

    public function testResolveNullableValue(): void
    {
        $container = new Container();
        $router = new Router();
        $errorHandler = new StubErrorHandler();
        $router->get('/users', [StubController::class, 'optionalUserId']);
        $kernel = new Kernel($container, $router, $errorHandler);
        $response = $kernel->handle(new Request('GET', '/users'));

        $this->assertSame(200, $response->status);
        $this->assertSame('GET:null', $response->body);
    }

    public function testResolveWithErrorHandler(): void
    {
        $container = new Container();
        $router = new Router();
        $errorHandler = new StubErrorHandler();
        $router->get('/error', [StubController::class, 'error']);
        $kernel = new Kernel($container, $router, $errorHandler);
        $response = $kernel->handle(new Request('GET', '/error'));

        $this->assertSame(500, $response->status);
        $this->assertSame('TestException', $response->body);
    }

    public function testRequiredNonNullArgumentCannotBeResolved(): void
    {
        $container = new Container();
        $router = new Router();
        $errorHandler = new StubErrorHandler();
        $router->get('/users', [StubController::class, 'requiredUserId']);
        $kernel = new Kernel($container, $router, $errorHandler);
        $response = $kernel->handle(new Request('GET', '/users'));

        $this->assertSame(500, $response->status);
        $this->assertSame(true, str_contains($response->body, '($userId) must be of type string'));
    }
}
