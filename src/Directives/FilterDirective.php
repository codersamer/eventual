<?php

namespace Codersamer\Eventual\Directives;

class FilterDirective
{
    public function __invoke($args)
    {
        return '<?php echo \Eventual::doFilter('.$args.') ?>';
    }
}
