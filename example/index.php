<?php

declare(strict_types=1);

use Hisui\Http\Kernel;
use Hisui\Http\Request;
use Hisui\Http\Response;
use Hisui\Http\ResponseEmitter;
use Hisui\DI\Container;
use Hisui\Routing\Router;
use Hisui\View\TemplateRenderer;

require __DIR__ . '/../autoload.php';

final class AppController {
    public function __construct(
        private TemplateRenderer $template,
    ) {
    }

    public function home(Request $request): Response
    {
        return new Response(body: $this->template->render('home'));
    }

    public function about(Request $request): Response
    {
        return new Response(body: $this->template->render('about'));
    }
}

$container = new Container();
$container->singleton(TemplateRenderer::class, function () {
    return new TemplateRenderer(__DIR__ . '/templates');
});

$router = new Router();
$router->get('/', [AppController::class, 'home']);
$router->get('/about', [AppController::class, 'about']);

$request = new Request(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
);
$kernel = new Kernel($container, $router);
$emitter = new ResponseEmitter();

$emitter->emit($kernel->handle($request));
