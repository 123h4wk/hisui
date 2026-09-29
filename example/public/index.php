<?php

declare(strict_types=1);

use Hisui\Http\Kernel;
use Hisui\Http\Request;
use Hisui\Http\ResponseEmitter;
use Hisui\DI\Container;
use Hisui\Routing\Router;
use Hisui\View\TemplateRenderer;
use Example\Controllers\PageController;
use Example\Error\AppErrorHandler;

$loader = require __DIR__ . '/../../autoload.php';
$loader->addNamespace('Example\\', __DIR__ . '/../src');

$container = new Container();
$container->singleton(TemplateRenderer::class, function () {
    return new TemplateRenderer(__DIR__ . '/../templates');
});

$router = new Router();
$router->get('/', [PageController::class, 'home']);
$router->get('/about', [PageController::class, 'about']);

$request = new Request(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
);
$errorHandler = new AppErrorHandler();

$kernel = new Kernel($container, $router, $errorHandler);
$emitter = new ResponseEmitter();

$response = $kernel->handle($request);
$emitter->emit($response);
