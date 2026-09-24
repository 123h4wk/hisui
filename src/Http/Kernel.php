<?php

declare(strict_types=1);

namespace Hisui\Http;

use Hisui\Routing\Router;

final class Kernel
{
    public function __construct(
        private Router $router,
    ) {
    }

    public function handle(Request $request): Response
    {
        $matched = $this->router->resolve(
            $request->method,
            $request->path,
        );

        if ($matched === null) {
            return new Response(404);
        }

        $controller = new $matched->action->class();
        $actionName = $matched->action->name;

        return $controller->{$actionName}($request);
    }
}
