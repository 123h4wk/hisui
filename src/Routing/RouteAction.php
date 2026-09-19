<?php

declare(strict_types=1);

namespace Hisui\Routing;

final readonly class RouteAction
{
    public function __construct(
        public string $class,
        public string $name,
    ) {
    }
}
