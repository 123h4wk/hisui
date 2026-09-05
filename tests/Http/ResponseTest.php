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

        $this->assertEqual($response->status, 201);
        $this->assertEqual($response->body, 'body');
        $this->assertEqual($response->headers['Foo'], 'Bar');
    }

    public function testCreateResponseWithDefaults(): void
    {
        $response = new Response();

        $this->assertEqual($response->status, 200);
        $this->assertEqual($response->body, '');
        $this->assertEqual($response->headers, []);
    }
}
