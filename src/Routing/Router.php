<?php

declare(strict_types=1);

namespace Hisui\Routing;

use Hisui\Http\HttpMethod;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $callback): void
    {
        $this->routes[] = new Route(HttpMethod::Get, $path, $callback);
    }

    public function post(string $path, callable $callback): void
    {
        $this->routes[] = new Route(HttpMethod::Post, $path, $callback);
    }

    public function put(string $path, callable $callback): void
    {
        $this->routes[] = new Route(HttpMethod::Put, $path, $callback);
    }

    public function patch(string $path, callable $callback): void
    {
        $this->routes[] = new Route(HttpMethod::Patch, $path, $callback);
    }

    public function delete(string $path, callable $callback): void
    {
        $this->routes[] = new Route(HttpMethod::Delete, $path, $callback);
    }

    public function options(string $path, callable $callback): void
    {
        $this->routes[] = new Route(HttpMethod::Options, $path, $callback);
    }

    public function head(string $path, callable $callback): void
    {
        $this->routes[] = new Route(HttpMethod::Head, $path, $callback);
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
