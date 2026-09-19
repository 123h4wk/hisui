<?php

declare(strict_types=1);

namespace Hisui\Routing;

use Hisui\Http\HttpMethod;

final class Route
{
    private HttpMethod $method;
    private \Closure $action;
    private array $patternList = [];
    private array $parameterList = [];

    public function __construct(HttpMethod $method, string $pattern, callable $callback)
    {
        $this->method = $method;
        $this->action = \Closure::fromCallable($callback);

        foreach ($this->createSegments($pattern) as $segment) {
            $matches = [];
            if (preg_match('/^\{([A-Za-z_][A-Za-z0-9_]*)\}$/', $segment, $matches)) {
                $parameterName = $matches[1];
                if (in_array($parameterName, $this->parameterList, true)) {
                    throw new \InvalidArgumentException(
                        '同じパラメータ名を登録することはできません。'
                    );
                }
                $this->patternList[] = null;
                $this->parameterList[] = $parameterName;
            } else {
                $this->patternList[] = $segment;
                $this->parameterList[] = null;
            }
        }
    }

    public function match(HttpMethod $method, string $path): ?RouteMatch
    {
        if ($method !== $this->method) {
            return null;
        }

        $segments = $this->createSegments($path);
        $segmentsCount = count($segments);

        if ($segmentsCount !== count($this->patternList)) {
            return null;
        }

        $params = [];

        for ($i = 0; $i < $segmentsCount; $i++) {
            $pattern = $this->patternList[$i];
            $parameter = $this->parameterList[$i];
            $value = $segments[$i];

            if ($pattern === null) {
                $params[$parameter] = $value;
                continue;
            }

            if ($pattern !== $value) {
                return null;
            }
        }

        return new RouteMatch($this->action, $params);
    }

    private function createSegments(string $path): array
    {
        $normalizedPath = '/' . trim($path, '/');
        return explode('/', $normalizedPath);
    }
}
