<?php

declare(strict_types=1);

namespace Hisui\Test;

final class TestRunner
{
    private array $testCaseClassNames = [];

    public function addTestCase(string $testCaseClassName): void
    {
        $this->testCaseClassNames[] = $testCaseClassName;
    }

    public function run(): TestResultCollection
    {

        $testResultCollection = new TestResultCollection();

        foreach ($this->testCaseClassNames as $testCaseClassName) {
            $class = new $testCaseClassName();
            $refClass = new \ReflectionClass($testCaseClassName);
            $methodRefs = $refClass->getMethods();

            foreach ($methodRefs as $methodRef) {
                $methodName = $methodRef->getName();
                $needsRun = !$methodRef->isStatic()
                    && $methodRef->isPublic()
                    && str_starts_with($methodName, 'test');
                if (!$needsRun) {
                    continue;
                }

                try {
                    $class->$methodName();
                    $testResultCollection->addPassedResult($methodName);
                } catch (AssertionFailed $e) {
                    $testResultCollection->addFailedResult($methodName, $e->getMessage());
                } catch (\Throwable $e) {
                    $testResultCollection->addFailedResult($methodName, $e->getMessage());
                }
            }
        }

        return $testResultCollection;
    }
}
