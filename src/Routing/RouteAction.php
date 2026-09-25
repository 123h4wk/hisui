<?php

declare(strict_types=1);

namespace Hisui\Routing;

final readonly class RouteAction
{
    public string $class;
    public string $name;

    public function __construct(string $class, string $name)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                '存在しないクラスです。',
            );
        }

        $refClass = new \ReflectionClass($class);
        if (!$refClass->isInstantiable()) {
            throw new \InvalidArgumentException(
                'インスタンス生成不能なクラスです。'
            );
        }

        if (!$refClass->hasMethod($name)) {
            throw new \InvalidArgumentException(
                '存在しないメソッドです。'
            );
        }

        $refMethod = $refClass->getMethod($name);
        if (!$refMethod->isPublic()) {
            throw new \InvalidArgumentException(
                '非公開のメソッドです。'
            );
        }

        $this->class = $class;
        $this->name = $name;
    }
}
