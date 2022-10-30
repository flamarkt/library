<?php

namespace Flamarkt\Library\Filter;

use Flarum\Filter\FilterInterface;
use Flarum\Filter\FilterState;
use Flarum\Search\AbstractRegexGambit;
use Flarum\Search\SearchState;

class MimeFilter extends AbstractRegexGambit implements FilterInterface
{
    protected function getGambitPattern(): string
    {
        return 'mime:(.+)';
    }

    protected function conditions(SearchState $search, array $matches, $negate)
    {
        // TODO: Implement conditions() method.
    }

    public function getFilterKey(): string
    {
        return 'mime';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        // TODO: Implement filter() method.
    }
}
