<?php

declare(strict_types=1);

namespace Hisui;

final class AutoLoader
{
    private array $map = [];

    public function addNamespace(string $prefix, string $baseDir): void
    {
        $prefix = rtrim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, '/') . '/';
        $this->map[$prefix] = $baseDir;
    }

    public function register(): void
    {
        spl_autoload_register($this->requireFile(...));
    }

    private function requireFile(string $class): bool
    {
        $classFilePath = $this->loadClassFilePath($class);
        if ($classFilePath) {
            require $classFilePath;
            return true;
        }

        return false;
    }

    private function loadClassFilePath(string $class): string | false
    {
        $prefix = $class;

        while(($pos = strrpos($prefix, '\\')) !== false) {
            $prefix = substr($prefix, 0, $pos);
            $mapKey = $prefix . '\\';
            if (array_key_exists($mapKey, $this->map)) {
                $path = $this->map[$mapKey] . substr($class, $pos + 1);
                $classFilePath = str_replace('\\', '/', $path) . '.php';
                if (file_exists($classFilePath)) {
                    return $classFilePath;
                }
            }
        }

        return false;
    }
}
