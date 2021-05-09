<?php

namespace Flamarkt\Library\Filter;

use Flarum\Filter\FilterInterface;
use Flarum\Filter\FilterState;

class MimeFilter implements FilterInterface
{
    public function getFilterKey(): string
    {
        return 'mime';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        // TODO: Implement filter() method.
    }
}
