<?php

declare(strict_types=1);

use Hisui\Http\HttpKernel;
use Hisui\Http\Request;
use Hisui\Http\Response;
use Hisui\Http\ResponseEmitter;
use Hisui\Routing\Router;

require __DIR__ . '/../autoload.php';

$controller = new class {
    public function home(Request $request): Response
    {
        return new Response(200, 'Home - Hisui');
    }

    public function about(Request $request): Response
    {
        return new Response(200, 'About - Hisui');
    }
};

$router = new Router();
$router->get('/', [get_class($controller), 'home']);
$router->get('/about', [get_class($controller), 'about']);

$request = new Request(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
);
$kernel = new HttpKernel($router);
$emitter = new ResponseEmitter();

$emitter->emit($kernel->handle($request));
