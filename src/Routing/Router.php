<?php

declare(strict_types=1);

namespace Hisui\Routing;

use Hisui\Http\HttpMethod;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $actionDef): void
    {
        $action = new RouteAction(...$actionDef);
        $this->routes[] = new Route(HttpMethod::Get, $path, $action);
    }

    public function post(string $path, array $actionDef): void
    {
        $action = new RouteAction(...$actionDef);
        $this->routes[] = new Route(HttpMethod::Post, $path, $action);
    }

    public function put(string $path, array $actionDef): void
    {
        $action = new RouteAction(...$actionDef);
        $this->routes[] = new Route(HttpMethod::Put, $path, $action);
    }

    public function patch(string $path, array $actionDef): void
    {
        $action = new RouteAction(...$actionDef);
        $this->routes[] = new Route(HttpMethod::Patch, $path, $action);
    }

    public function delete(string $path, array $actionDef): void
    {
        $action = new RouteAction(...$actionDef);
        $this->routes[] = new Route(HttpMethod::Delete, $path, $action);
    }

    public function options(string $path, array $actionDef): void
    {
        $action = new RouteAction(...$actionDef);
        $this->routes[] = new Route(HttpMethod::Options, $path, $action);
    }

    public function head(string $path, array $actionDef): void
    {
        $action = new RouteAction(...$actionDef);
        $this->routes[] = new Route(HttpMethod::Head, $path, $action);
    }

    public function resolve(HttpMethod $method, string $path): ?RouteMatch
    {
        foreach ($this->routes as $route) {
            $matched = $route->match($method, $path);
            if ($matched instanceof RouteMatch) {
                return $matched;
            }
        }

        return null;
    }
}
