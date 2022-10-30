<?php

namespace Flamarkt\Library\File\Event;

use Flamarkt\Library\File;
use Flarum\User\User;

class Saving
{
    public $file;
    public $actor;
    public $data;

    public function __construct(File $file, User $actor, array $data = [])
    {
        $this->file = $file;
        $this->actor = $actor;
        $this->data = $data;
    }
}
