<?php

namespace Flamarkt\Library\File\Event;

use Flamarkt\Library\File;
use Flarum\User\User;

class DescriptionChanged
{
    public $file;
    public $oldDescription;
    public $actor;

    public function __construct(File $file, $oldDescription, User $actor = null)
    {
        $this->file = $file;
        $this->oldDescription = $oldDescription;
        $this->actor = $actor;
    }
}
