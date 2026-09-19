<?php

declare(strict_types=1);

namespace Hisui\Tests\View;

use Hisui\View\TemplateRenderer;
use Hisui\Test\TestCase;

final class TemplateRendererTest extends TestCase
{
    public function testRender(): void
    {
        $template = new TemplateRenderer(__DIR__ . '/../fixtures/view');
        $result = $template->render('template', ['title' => 'Title']);
        $this->assertSame("<h1>Title</h1>\n", $result);
    }

    public function testRenderWithErrors(): void
    {
        $this->assertThrows(\ParseError::class, function () {
            $template = new TemplateRenderer(__DIR__ . '/../fixtures/view');
            $template->render('syntax-error');
        });
    }

    public function testThrowsErrorWhenTemplateNotFound(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            $template = new TemplateRenderer('');
            $template->render('invalid_filename');
        });
    }
}
