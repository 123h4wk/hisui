<?php

declare(strict_types=1);

namespace Hisui\Tests\Http;

use Hisui\Http\Request;
use Hisui\Http\HttpMethod;
use Hisui\Test\TestCase;

final class RequestTest extends TestCase
{
    public function testCreateRequest(): void
    {
        $request = new Request(
            'GET',
            '/users'
        );

        $this->assertSame(HttpMethod::Get, $request->getMethod());
        $this->assertSame('/users', $request->getPath());
        $this->assertSame(null, $request->getQueryParam('id'));
        $this->assertSame([], $request->getListQueryParam('id'));

    }

    public function testCreateRequestWithQuery(): void
    {
        $request = new Request(
            'GET',
            '/users?id=100'
        );

        $this->assertSame(HttpMethod::Get, $request->getMethod());
        $this->assertSame('/users', $request->getPath());
        $this->assertSame('100', $request->getQueryParam('id'));
        $this->assertSame([], $request->getListQueryParam('id'));
    }

    public function testCreateRequestWithListQuery(): void
    {
        $request = new Request(
            'GET',
            '/list?type=programing&languages[]=php&languages[]=java'
        );

        $this->assertSame(HttpMethod::Get, $request->getMethod());
        $this->assertSame('/list', $request->getPath());
        $this->assertSame('programing', $request->getQueryParam('type'));
        $this->assertSame('php', $request->getListQueryParam('languages')[0]);
        $this->assertSame('java', $request->getListQueryParam('languages')[1]);
    }
}
