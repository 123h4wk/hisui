<?php

declare(strict_types=1);

use Hisui\Http\HttpKernel;
use Hisui\Http\Request;
use Hisui\Http\ResponseEmitter;

require __DIR__ . '/../autoload.php';

$request = new Request(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
);
$kernel = new HttpKernel();
$emitter = new ResponseEmitter();

$emitter->emit($kernel->handle($request));
