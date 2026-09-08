<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\Request;
use Hisui\Http\HttpMethod;
use Hisui\Test\TestCase;

final class RequestTest extends TestCase
{
    public function testCreate(): void
    {
        $request = new Request(
            'GET',
            '/users'
        );

        $this->assertSame(HttpMethod::Get, $request->method);
        $this->assertSame('/users', $request->path);
        $this->assertSame(null, $request->getQueryParam('id'));
        $this->assertSame([], $request->getListQueryParam('id'));
    }

    public function testCreateWithQuery(): void
    {
        $request = new Request(
            'GET',
            '/users?id=100'
        );

        $this->assertSame(HttpMethod::Get, $request->method);
        $this->assertSame('/users', $request->path);
        $this->assertSame('100', $request->getQueryParam('id'));
        $this->assertSame([], $request->getListQueryParam('id'));
    }

    public function testCreateWithListQuery(): void
    {
        $request = new Request(
            'GET',
            '/list?type=programing&languages[]=php&languages[]=java'
        );

        $this->assertSame(HttpMethod::Get, $request->method);
        $this->assertSame('/list', $request->path);
        $this->assertSame('programing', $request->getQueryParam('type'));
        $this->assertSame('php', $request->getListQueryParam('languages')[0]);
        $this->assertSame('java', $request->getListQueryParam('languages')[1]);
    }

    public function testCreateWithInvalidMethod(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Request('INVALID', '/users');
        });
    }

    public function testCreateWithInvalidTarget(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new Request('GET', 'users');
        });
    }
}
