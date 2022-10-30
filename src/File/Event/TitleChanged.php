<?php

namespace Flamarkt\Library\File\Event;

use Flamarkt\Library\File;
use Flarum\User\User;

class TitleChanged
{
    public $file;
    public $oldTitle;
    public $actor;

    public function __construct(File $file, $oldTitle, User $actor = null)
    {
        $this->file = $file;
        $this->oldTitle = $oldTitle;
        $this->actor = $actor;
    }
}
