<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\Response;
use Hisui\Test\TestCase;

final class ResponseTest extends TestCase
{
    public function testCreate(): void
    {
        $response = new Response(201, 'body', ['Foo' => 'Bar']);

        $this->assertSame(201, $response->status);
        $this->assertSame('body', $response->body);
        $this->assertSame('Bar', $response->headers['Foo']);
    }

    public function testCreateWithDefaults(): void
    {
        $response = new Response();

        $this->assertSame(200, $response->status);
        $this->assertSame('', $response->body);
        $this->assertSame([], $response->headers);
    }

    public function testCreateWithValidStatus(): void
    {
        $response = new Response(100);
        $this->assertSame(100, $response->status);

        $response = new Response(599);
        $this->assertSame(599, $response->status);
    }

    public function testCreateWithValidHeaderCharacter(): void
    {
        $response = new Response(200, '', ['X-Custom_Header.1' => 'Value']);
        $this->assertSame('Value', $response->headers['X-Custom_Header.1']);
    }

    public function testCreateWithInvalidStatus(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(99);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(600);
        });
    }

    public function testCreateWithInvalidHeader(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', [100 => 'Foo']);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', ['Foo' => true]);
        });
    }

    public function testCreateWithInvalidHeaderCharacter(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', ['' => 'Value']);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', ['Foo Bar' => 'Value']);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', ['Foo:Bar' => 'Value']);
        });
    }

    public function testCreateWithControlCharacter(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', ['key' => "a\r"]);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', ['key' => "b\n"]);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Response(200, '', ['key' => "c\0"]);
        });
    }
}
