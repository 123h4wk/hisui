<?php

declare(strict_types=1);

namespace Hisui\Http;

final class HttpKernel
{
    public function handle(Request $request): Response
    {
        return new Response(200, 'Hisui');
    }
}
