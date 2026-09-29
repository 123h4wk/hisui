<?php

declare(strict_types=1);

namespace Hisui\Http;

use Hisui\DI\Container;
use Hisui\Routing\Router;
use Hisui\Http\Error\ErrorHandler;

final class Kernel
{
    public function __construct(
        private Container $container,
        private Router $router,
        private ErrorHandler $errorHandler,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $matched = $this->router->resolve(
                $request->method,
                $request->path,
            );

            if ($matched === null) {
                return new Response(404);
            }

            $controller = $this->container->get($matched->action->class);
            $actionName = $matched->action->name;
            $refAction = new \ReflectionMethod($controller, $actionName);
            $actionArgs = $this->resolveActionArgs(
                $refAction,
                $matched->params,
                $request,
            );
            return $controller->{$actionName}(...$actionArgs);
        } catch (\Throwable $e) {
            return $this->errorHandler->handle($e, $request);
        }
    }

    private function resolveActionArgs(
        \ReflectionMethod $refAction,
        array $params,
        Request $request,
    ): array {
        $result = [];

        foreach ($refAction->getParameters() as $refParam) {
            $refType = $refParam->getType();
            $paramName = $refParam->getName();

            if ($refType instanceof \ReflectionNamedType) {
                if ($refType->getName() === Request::class) {
                    $result[$paramName] = $request;
                    continue;
                }
            }

            if (array_key_exists($paramName, $params)) {
                $result[$paramName] = $params[$paramName];
            } elseif ($refParam->isDefaultValueAvailable()) {
                $result[$paramName] = $refParam->getDefaultValue();
            } else {
                $result[$paramName] = null;
            }
        }

        return $result;
    }
}
