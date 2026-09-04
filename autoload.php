<?php

declare(strict_types=1);

require __DIR__ . '/src/AutoLoader.php';

$autoLoader = new Hisui\AutoLoader();
$autoLoader->addNamespace('Hisui\\', __DIR__ . '/src');
$autoLoader->register();

return $autoLoader;
