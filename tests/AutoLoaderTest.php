<?php

declare(strict_types=1);

namespace Hisui\Tests;

use Hisui\AutoLoader;
use Hisui\Test\TestCase;
use AutoLoad\MainClass;
use AutoLoad\Deep\ModuleClass;
use AutoLoad\Another\CoreClass;

final class AutoLoaderTest extends TestCase
{
    public function testLoadFile(): void
    {
        $autoLoader = new AutoLoader();
        $autoLoader->addNamespace('AutoLoad\\', __DIR__ . '/fixtures/autoload');
        $autoLoader->addNamespace('AutoLoad\\Another', __DIR__ . '/fixtures/autoload/another');
        $autoLoader->register();

        $this->assertSame(true, new MainClass() instanceof MainClass);
        $this->assertSame(true, new ModuleClass() instanceof ModuleClass);
        $this->assertSame(true, new CoreClass() instanceof CoreClass);
    }
}
