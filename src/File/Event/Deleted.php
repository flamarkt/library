<?php

namespace Flamarkt\Library\File\Event;

use Flamarkt\Library\File;
use Flarum\User\User;

class Deleted
{
    public $file;
    public $actor;

    public function __construct(File $file, User $actor = null)
    {
        $this->file = $file;
        $this->actor = $actor;
    }
}
