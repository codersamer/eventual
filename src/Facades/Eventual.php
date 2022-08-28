<?php

namespace Codersamer\Eventual\Facades;

use Illuminate\Support\Facades\Facade;

class Eventual extends Facade
{
    protected static function getFacadeAccessor() { return 'eventual'; }
}
