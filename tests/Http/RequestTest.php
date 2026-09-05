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

        $this->assertEqual($request->getMethod(), HttpMethod::Get);
        $this->assertEqual($request->getPath(), '/users');
        $this->assertEqual($request->getQueryParam('id'), null);
        $this->assertEqual($request->getListQueryParam('id'), []);

    }

    public function testCreateRequestWithQuery(): void
    {
        $request = new Request(
            'GET',
            '/users?id=100'
        );

        $this->assertEqual($request->getMethod(), HttpMethod::Get);
        $this->assertEqual($request->getPath(), '/users');
        $this->assertEqual($request->getQueryParam('id'), '100');
        $this->assertEqual($request->getListQueryParam('id'), []);
    }

    public function testCreateRequestWithListQuery(): void
    {
        $request = new Request(
            'GET',
            '/list?type=programing&languages[]=php&languages[]=java'
        );

        $this->assertEqual($request->getMethod(), HttpMethod::Get);
        $this->assertEqual($request->getPath(), '/list');
        $this->assertEqual($request->getQueryParam('type'), 'programing');
        $this->assertEqual($request->getListQueryParam('languages')[0], 'php');
        $this->assertEqual($request->getListQueryParam('languages')[1], 'java');
    }
}
