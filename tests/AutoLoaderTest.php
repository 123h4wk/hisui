<?php

declare(strict_types=1);

require __DIR__ . '/../src/AutoLoader.php';

$autoLoader = new Hisui\AutoLoader();
$autoLoader->addNamespace('AutoLoad\\', __DIR__ . '/fixtures/autoload');
$autoLoader->addNamespace('AutoLoad\\Another', __DIR__ . '/fixtures/autoload/another');
$autoLoader->register();

use AutoLoad\MainClass;
use AutoLoad\Deep\ModuleClass;
use AutoLoad\Another\CoreClass;

assert(new MainClass() instanceof MainClass);
assert(new ModuleClass() instanceof ModuleClass);
assert(new CoreClass() instanceof CoreClass);
