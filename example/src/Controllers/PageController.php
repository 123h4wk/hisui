<?php

declare(strict_types=1);

namespace Example\Controllers;

use Hisui\Http\Response;
use Hisui\View\TemplateRenderer;

final class PageController
{
    public function __construct(
        private TemplateRenderer $template,
    ) {
    }

    public function home(): Response
    {
        return new Response(body: $this->template->render('home'));
    }

    public function about(): Response
    {
        return new Response(body: $this->template->render('about'));
    }
}
