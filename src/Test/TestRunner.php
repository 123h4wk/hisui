<?php

declare(strict_types=1);

namespace Hisui\Test;

final class TestRunner
{
    private array $testCaseClassNames = [];

    public function addTestCase(string $testCaseClassName): void
    {
        if (!class_exists($testCaseClassName)) {
            throw new \InvalidArgumentException('クラスが存在しません。');
        }

        $refClass = new \ReflectionClass($testCaseClassName);

        if (!$refClass->isSubclassOf(TestCase::class)) {
            throw new \InvalidArgumentException(
                'TestCaseを継承したクラスではありません。'
            );
        }

        if (!$refClass->isInstantiable()) {
            throw new \InvalidArgumentException(
                'インスタンス生成可能なクラスではありません。'
            );
        }

        $constructor = $refClass->getConstructor();

        if (
            $constructor !== null
            && $constructor->getNumberOfRequiredParameters() > 0
        ) {
            throw new \InvalidArgumentException(
                '引数なしでインスタンス生成可能なクラスではありません。'
            );
        }

        $this->testCaseClassNames[] = $testCaseClassName;
    }

    public function run(): TestResultCollection
    {
        $testResultCollection = new TestResultCollection();

        foreach ($this->testCaseClassNames as $testCaseClassName) {
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
                    $class = new $testCaseClassName();
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
