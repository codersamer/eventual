<?php

namespace Codersamer\Eventual\Providers;

use Codersamer\Eventual\Directives\ActionDirective;
use Codersamer\Eventual\Directives\FilterDirective;
use Codersamer\Eventual\Services\Eventual;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class EventualServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('eventual', function($app){
            return new Eventual($app);
        });
        Blade::directive('action', new ActionDirective);
        Blade::directive('filter', new FilterDirective);
    }

    public function boot()
    {

    }
}
