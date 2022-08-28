<?php

namespace Codersamer\Eventual\Traits;

use Illuminate\Database\Eloquent\Concerns\HasEvents;

trait Eventable
{
    use HasFilters, HasEvents;
}
