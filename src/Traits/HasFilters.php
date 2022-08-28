<?php

namespace Codersamer\Eventual\Traits;

use Closure;
use Codersamer\Eventual\Facades\Eventual;

trait HasFilters
{
    public static function doFilter($name, ...$params)
    {
        Eventual::setScope(static::class);
        return Eventual::doFilter($name, ...$params);
    }

    public static function onFilter($name, String|array|Closure $callback, int $order = 10)
    {
        Eventual::setScope(static::class);
        Eventual::onFilter($name, $callback, $order);
    }
}
