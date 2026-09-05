<?php

declare(strict_types=1);

namespace Hisui\Http;

readonly final class Response
{
    public function __construct(
        public int $status = 200,
        public string $body = '',
        public array $headers = [],
    ) {
        if ($status < 100 || $status > 599) {
            throw new \InvalidArgumentException(
                '無効なHTTPステータスコードです。'
            );
        }

        foreach($headers as $headerName => $headerValue) {
            if (!is_string($headerName) || !is_string($headerValue)) {
                throw new \InvalidArgumentException(
                    'HTTPヘッダー名と値は文字列である必要があります。'
                );
            }

            if (!preg_match("/^[!#$%&'*+\-.^_`|~0-9A-Za-z]+$/D", $headerName)) {
                throw new \InvalidArgumentException('無効なHTTPヘッダー名です。');
            }

            if (
                str_contains($headerValue, "\r")
                || str_contains($headerValue, "\n")
                || str_contains($headerValue, "\0")
            ) {
                throw new \InvalidArgumentException(
                    'HTTPヘッダー値に改行が含まれています。'
                );
            }
        }
    }
}
