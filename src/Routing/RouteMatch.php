<?php

declare(strict_types=1);

namespace Hisui\Routing;

final readonly class RouteMatch
{
    public function __construct(
        public \Closure $action,
        public array $params
    ) {
    }
}
