<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\Response;
use Hisui\Test\TestCase;

final class ResponseTest extends TestCase
{
    public function testCreateResponse(): void
    {
        $response = new Response(201, 'body', ['Foo' => 'Bar']);

        $this->assertSame(201, $response->status);
        $this->assertSame('body', $response->body);
        $this->assertSame('Bar', $response->headers['Foo']);
    }

    public function testCreateResponseWithDefaults(): void
    {
        $response = new Response();

        $this->assertSame(200, $response->status);
        $this->assertSame('', $response->body);
        $this->assertSame([], $response->headers);
    }
}
