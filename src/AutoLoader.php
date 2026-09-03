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

    private function loadClass(string $class): string | false
    {
        $prefix = $class;

        while(($pos = strrpos($prefix, '\\')) !== false) {
            $prefix = substr($prefix, 0, $pos);
            $mapKey = $prefix . '\\';
            if (array_key_exists($mapKey, $this->map)) {
                $fullPath = $this->map[$mapKey] . substr($class, $pos + 1);
                $filePath = str_replace('\\', '/', $fullPath) . '.php';
                if (file_exists($filePath)) {
                    return $filePath;
                }
            }
        }

        return false;
    }

    private function requireFile(string $class): bool
    {
        $classPath = $this->loadClass($class);
        if ($classPath) {
            require $classPath;
            return true;
        } else {
            return false;
        }
    }
}
