<?php

namespace Flamarkt\Library;

use Flarum\Search\AbstractSearcher;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class FileSearcher extends AbstractSearcher
{
    protected function getQuery(User $actor): Builder
    {
        return File::whereVisibleTo($actor);
    }
}
