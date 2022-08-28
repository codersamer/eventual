<?php

namespace Codersamer\Eventual\Services;

use Closure;
use Codersamer\Eventual\Traits\Eventable;
use Exception;
use Illuminate\Foundation\Application;
use stdClass;

class Eventual
{
    protected $actions = [];

    protected $filters = [];

    protected $scope = 'default';

    public function __construct(protected Application $app) { }

    public function setScope($scope) { $this->scope = $scope; }

    protected function resetScope() { $this->scope = 'default'; }

    public function onAction($name, array|Closure|String $callback, $order = 10)
    {
        $dispatcher = $this->scope;
        $this->makeSureDispatcherExists($dispatcher);
        $dispatcherName = $this->getDispatcherName($dispatcher);
        $this->actions[$dispatcherName][$name] = isset($this->actions[$dispatcherName][$name]) ? $this->actions[$dispatcherName][$name] : [];
        $action = new stdClass;
        $action->callback = $callback;
        $action->order = $order;
        $this->actions[$dispatcherName][$name][] = $action;
        $this->resetScope();
    }

    public function onFilter($name, array|Closure|String $callback, $order = 10)
    {
        $dispatcher = $this->scope;
        $this->makeSureDispatcherExists($dispatcher);
        $dispatcherName = $this->getDispatcherName($dispatcher);
        $this->filters[$dispatcherName][$name] = isset($this->filters[$dispatcherName][$name]) ? $this->filters[$dispatcherName][$name] : [];
        $filter = new stdClass;
        $filter->callback = $callback;
        $filter->order = $order;
        $this->filters[$dispatcherName][$name][] = $filter;
        $this->resetScope();
    }


    public function doFilter($name, ...$arguments)
    {
        $dispatcher = $this->scope;
        $haystack = $arguments[0];
        foreach($this->getFilterHandlers($name, $dispatcher) as $filterHandler)
        {
            $nextArguments = $arguments;
            $nextArguments[0] = $haystack;
            $tempValue = $this->invokeHandler($filterHandler, ...$nextArguments);
            $haystack = $tempValue;
        }
        $this->resetScope();
        return $haystack;
    }

    public function doAction($name, ...$arguments)
    {
        $dispatcher = $this->scope;
        foreach($this->getActionsHandlers($name, $dispatcher) as $actionHandler)
        {
            $this->invokeHandler($actionHandler, ...$arguments);
        }
        $this->resetScope();
    }

    protected function invokeHandler($handler, ...$params)
    {
        $handler = $handler->callback;
        if(is_string($handler) && function_exists($handler))
        { return call_user_func($handler, ...$params); }
        if(is_array($handler) && count($handler) == 2)
        {
            if(is_callable($handler)) { return call_user_func($handler, ...$params); }
            if(class_exists($handler[0]))
            {
                try
                {
                    $objectiveCallback = $handler;
                    $class = $handler[0];
                    $objectiveCallback[0] = new $class;
                    if(is_callable($objectiveCallback))
                    { call_user_func($objectiveCallback, ...$params); }
                }
                catch(Exception $ex) { }

            }
        }
        if(is_callable($handler)) { return call_user_func($handler, ...$params); }
    }

    protected function getFilterHandlers($name, $dispatcher = 'default')
    {
        $dispatcherName = $this->getDispatcherName($dispatcher);
        $this->makeSureDispatcherExists($dispatcher);
        $handlers = isset($this->filters[$dispatcherName][$name]) && count($this->filters[$dispatcherName][$name])  ? $this->filters[$dispatcherName][$name] : [];
        return $this->sortHandlers($handlers);
    }

    protected function getActionsHandlers($name, $dispatcher = 'default')
    {
        $dispatcherName = $this->getDispatcherName($dispatcher);
        $this->makeSureDispatcherExists($dispatcher);
        $handlers = isset($this->actions[$dispatcherName][$name]) && count($this->actions[$dispatcherName][$name])  ? $this->actions[$dispatcherName][$name] : [];
        return $this->sortHandlers($handlers);
    }

    protected function sortHandlers(array $handlers)
    {
        usort($handlers, function($a, $b){
            return strcmp($a->order, $b->order);
        });
        return $handlers;
    }

    protected function getDispatcherName(String|object $dispatcher)
    {
        return is_string($dispatcher) ? $dispatcher : get_class($dispatcher);
    }

    protected function makeSureDispatcherExists(String|object $dispatcher)
    {
        $name = $this->getDispatcherName($dispatcher);
        $this->actions[$name] = isset($this->actions[$name]) ? $this->actions[$name] : [];
        $this->filters[$name] = isset($this->filters[$name]) ? $this->filters[$name] : [];
    }

    protected function actionHasHandlers($name, $dispatcher = 'default')
    {
        $dispatcherName = $this->getDispatcherName($dispatcher);
        $this->makeSureDispatcherExists($dispatcher);
        return isset($this->actions[$dispatcherName][$name]) && count($this->actions[$dispatcherName][$name]) > 0;
    }

    protected function filterHasHandlers($name, $dispatcher = 'default')
    {
        $dispatcherName = $this->getDispatcherName($dispatcher);
        $this->makeSureDispatcherExists($dispatcher);
        return isset($this->filters[$dispatcherName][$name]) && count($this->filters[$dispatcherName][$name]) > 0;
    }
}
