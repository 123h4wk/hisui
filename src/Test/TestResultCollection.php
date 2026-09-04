<?php

declare(strict_types=1);

namespace Hisui\Test;

final class TestResultCollection
{
    private array $passedResults = [];
    private array $failedResults = [];

    public function getPassedCount(): int
    {
        return count($this->passedResults);
    }

    public function getFailedCount(): int
    {
        return count($this->failedResults);
    }

    public function getTotalCount(): int
    {
        return $this->getPassedCount() + $this->getFailedCount();
    }

    public function addPassedResult(string $methodName): void
    {
        $this->passedResults[] = new TestResult(true, $methodName, '');
    }

    public function hasFailures(): bool
    {
        return $this->getFailedCount() !== 0;
    }

    public function getExitCode(): int
    {
        return $this->hasFailures() ? 1 : 0;
    }

    public function addFailedResult(string $methodName, string $errorMessage): void
    {
        $this->failedResults[] = new TestResult(false, $methodName, $errorMessage);
    }

    public function report(): void
    {
        echo sprintf(
            "%s件中%s件成功%s件失敗\n",
            $this->getTotalCount(),
            $this->getPassedCount(),
            $this->getFailedCount(),
        );

        if (!$this->hasFailures()) {
            return;
        }

        foreach ($this->failedResults as $failedResult) {
            echo $failedResult->methodName;
            echo "\n";
            echo $failedResult->errorMessage;
            echo "\n";
        }
    }
}
