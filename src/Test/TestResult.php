<?php

declare(strict_types=1);

namespace Hisui\Test;

final class TestResult
{
    public function __construct(
      readonly bool $isPass,
      readonly string $methodName,
      readonly string $errorMessage,
    ) {}
}
