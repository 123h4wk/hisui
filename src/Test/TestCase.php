<?php

declare(strict_types=1);

namespace Hisui\Test;

class TestCase
{
    protected function assertEqual(mixed $actual, mixed $expected): void
    {
        if ($actual !== $expected) {
            throw new AssertionFailed(sprintf(
                '期待値: %s | 結果: %s',
                var_export($expected, true),
                var_export($actual, true),
            ));
        }
    }
}
