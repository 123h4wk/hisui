<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\Kernel;
use Hisui\Http\Request;
use Hisui\Http\Response;
use Hisui\Routing\Router;
use Hisui\Test\TestCase;

final class KernelTest extends TestCase
{
    public function testHandleRequest(): void
    {
        $controller = new class {
            public function index(Request $request): Response
            {
                return new Response(200, $request->method->value);
            }
        };
        $router = new Router();
        $router->get('/', [get_class($controller), 'index']);
        $kernel = new Kernel($router);
        $response = $kernel->handle(new Request('GET', '/'));

        $this->assertSame(200, $response->status);
        $this->assertSame('GET', $response->body);
    }
}
