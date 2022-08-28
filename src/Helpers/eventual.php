<?php

use Codersamer\Eventual\Facades\Eventual;

if(!function_exists('on_action'))
{
    function on_action($name, String|array|Closure $callback, $order = 10)
    { Eventual::onAction($name, $callback, $order); }
}

if(!function_exists('do_action'))
{
    function do_action($name, ...$arguments)
    { Eventual::doAction($name, ...$arguments); }
}

if(!function_exists('on_filter'))
{
    function on_filter($name, String|array|Closure $callback, $order)
    { Eventual::onFilter($name, $callback, $order); }
}

if(!function_exists('do_filter'))
{
    function do_filter($name, ...$arguments)
    { Eventual::doFilter($name, ...$arguments); }
}