<?php

namespace Codersamer\Eventual\Traits;

use Closure;
use Codersam\Eventual\Facades\Eventual;

trait HasActions
{
    public static function doAction($name, ...$params)
    {
        Eventual::setScope(static::class);
        Eventual::doAction($name, ...$params);
    }

    public static function onAction($name, String|array|Closure $callback, int $order = 10)
    {
        Eventual::setScope(static::class);
        Eventual::onAction($name, $callback, $order);
    }
}
