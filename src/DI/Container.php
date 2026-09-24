<?php

declare(strict_types=1);

namespace Hisui\DI;

final class Container
{
    private $factories = [];
    private $instances = [];
    private $resolvingIds = [];

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (isset($this->resolvingIds[$id])) {
            throw new \InvalidArgumentException(
                '依存が循環しています。'
            );
        }

        $this->resolvingIds[$id] = true;

        try {
            if (!array_key_exists($id, $this->factories)) {
                $this->registerAutowiringFactory($id);
            }
            $this->instances[$id] = $this->factories[$id]();

            return $this->instances[$id];
        } finally {
            unset($this->resolvingIds[$id]);
        }
    }

    public function singleton(string $id, callable $factory): void
    {
        if (array_key_exists($id, $this->factories)) {
            throw new \InvalidArgumentException(
                '同名の依存が既に登録されています。'
            );
        }

        $this->factories[$id] = $factory;
    }

    private function registerAutowiringFactory(string $id): void
    {
        if (!class_exists($id)) {
            throw new \InvalidArgumentException(
                '非クラス依存のため、ファクトリー登録なしで解決できません。'
            );
        }

        $refClass = new \ReflectionClass($id);
        if (!$refClass->isInstantiable()) {
            throw new \InvalidArgumentException(
                'インスタンス生成不能なクラス依存のため、ファクトリー登録なしで解決できません。'
            );
        }

        $refConstructor = $refClass->getConstructor();
        if (!$refConstructor) {
            $this->singleton($id, fn () => new $id());
            return;
        }

        $args = [];
        foreach ($refConstructor->getParameters() as $refParam) {
            $refType = $refParam->getType();
            if (!$refType instanceof \ReflectionNamedType || $refType->isBuiltin()) {
                if ($refParam->isDefaultValueAvailable()) {
                    $args[$refParam->getName()] = $refParam->getDefaultValue();
                } else {
                    throw new \InvalidArgumentException(
                        'コンストラクタ引数を自動解決できません。',
                    );
                }
            } else {
                $args[$refParam->getName()] = $this->get($refType->getName());
            }
        }
        $this->singleton($id, fn () => new $id(...$args));
    }
}
