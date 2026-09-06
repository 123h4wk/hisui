<?php

declare(strict_types=1);

namespace Hisui\Test;

class TestCase
{
    protected function assertSame(mixed $expected, mixed $actual): void
    {
        if ($actual !== $expected) {
            throw new AssertionFailed(sprintf(
                '期待値: %s | 結果: %s',
                var_export($expected, true),
                var_export($actual, true),
            ));
        }
    }

    protected function assertThrows(string $expected, \Closure $callback): void
    {
        $isThrow = false;

        try {
            $callback();
        } catch (\Throwable $e) {
            $isThrow = true;

            if (!$e instanceof $expected) {
                throw new AssertionFailed(sprintf(
                    '期待値: %s | 結果: %s',
                    var_export($expected, true),
                    var_export($e::class, true),
                ));
            }
        }

        if (!$isThrow) {
            throw new AssertionFailed(sprintf(
                '期待値: %s | 結果: エラーが発生しませんでした。',
                var_export($expected, true),
            ));
        }
    }
}
