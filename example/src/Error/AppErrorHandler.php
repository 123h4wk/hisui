<?php

declare(strict_types=1);

namespace Example\Error;

use Hisui\Http\Request;
use Hisui\Http\Response;
use Hisui\Http\Error\ErrorHandler;

final class AppErrorHandler implements ErrorHandler
{
    public function handle(\Throwable $e, Request $request): Response
    {
        return new Response(500, $e->getMessage());
    }
}
