<?php

declare(strict_types=1);

namespace Hisui\Http\Error;

use Hisui\Http\Request;
use Hisui\Http\Response;

interface ErrorHandler
{
    public function handle(\Throwable $e, Request $request): Response;
}
