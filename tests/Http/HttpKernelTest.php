<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\HttpKernel;
use Hisui\Http\Request;
use Hisui\Test\TestCase;

final class HttpKernelTest extends TestCase
{
    public function testCreate(): void
    {
        $kernel = new HttpKernel();
        $request = new Request('GET', '/');
        $response = $kernel->handle($request);

        $this->assertEqual($response->status, 200);
        $this->assertEqual($response->body, 'Hisui');
    }
}
