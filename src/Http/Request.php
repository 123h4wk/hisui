<?php

declare(strict_types=1);

namespace Hisui\Http;

final class Request
{
    public readonly HttpMethod $method;
    public readonly string $path;
    private readonly array $queryParams;

    public function __construct(
        string $httpMethodString,
        string $requestTarget,
    ) {
        $method = HttpMethod::tryFrom($httpMethodString);
        if ($method === null) {
            throw new \InvalidArgumentException(
                '不正なHTTPメソッドです。'
            );
        }
        $this->method = $method;

        [$path, $query] = array_pad(explode('?', $requestTarget, 2), 2, '');

        if (!str_starts_with($requestTarget, '/')) {
            throw new \InvalidArgumentException(
                'リクエストターゲットは/で始まる必要があります。'
            );
        }

        $this->path = $path;
        parse_str($query, $queryParams);
        $this->queryParams = $queryParams;
    }

    public function getQueryParam(string $name): ?string
    {
        if (
            array_key_exists($name, $this->queryParams)
            && is_string($this->queryParams[$name])
        ) {
            return $this->queryParams[$name];
        }

        return null;
    }

    public function getListQueryParam(string $name): array
    {
        if (
            array_key_exists($name, $this->queryParams)
            && is_array($this->queryParams[$name])
        ) {
            return $this->queryParams[$name];
        }

        return [];
    }
}
