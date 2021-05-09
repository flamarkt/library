<?php

namespace Flamarkt\Library\Scope;

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class View
{
    public function __invoke(User $actor, Builder $query)
    {
        if ($actor->cannot('backoffice')) {
            $query->whereRaw('0=1');
        }
    }
}
