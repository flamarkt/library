<?php

namespace Flamarkt\Library;

use Flarum\Filter\AbstractFilterer;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class FileFilterer extends AbstractFilterer
{
    protected function getQuery(User $actor): Builder
    {
        return File::whereVisibleTo($actor);
    }
}
