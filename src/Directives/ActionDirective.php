<?php

namespace Codersamer\Eventual\Directives;

class ActionDirective
{
    public function __invoke($args)
    {
        return '<?php \Eventual::doAction('.$args.') ?>';
    }
}
