<?php

declare(strict_types=1);

namespace Hisui\View;

final class TemplateRenderer
{
    public function __construct(
        private string $templateDir,
    ) {
    }

    public function render(string $templateName, array $params = []): string
    {
        $templateFile = $this->templateDir . '/' .$templateName . '.php';

        if (!is_file($templateFile)) {
            throw new \InvalidArgumentException(
                'テンプレートファイルが存在しません。'
            );
        }

        $isolatedRenderer = $this->createIsolatedRenderer($templateFile, $params);

        return $isolatedRenderer();
    }

    private function createIsolatedRenderer(
        string $__file__,
        array $__params__
    ): \Closure {
        return function () use ($__file__, $__params__): string {
            extract(self::viewHelpers());
            extract($__params__, EXTR_SKIP);

            ob_start();
            try {
                require $__file__;
                return ob_get_clean();
            } catch (\Throwable $e) {
                ob_get_clean();
                throw $e;
            }
        };
    }

    private static function viewHelpers(): array
    {
        return [
            'esc' => static fn (...$args) => self::escapeHtmlSpecialChars(...$args),
        ];
    }

    private static function escapeHtmlSpecialChars(mixed $value): string
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8',
        );
    }
}
