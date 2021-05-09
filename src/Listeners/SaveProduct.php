<?php

namespace Flamarkt\Library\Listeners;

use Flamarkt\Core\Product\Event\Saving;
use Flamarkt\Library\File;
use Illuminate\Support\Arr;

class SaveProduct
{
    public function handle(Saving $event)
    {
        $thumbnail = Arr::get($event->data, 'data.relationships.thumbnail.data', false);

        if ($thumbnail === false) {
            return;
        }

        $event->actor->assertCan('backoffice');

        if ($thumbnail) {
            $file = File::query()->where('uid', Arr::get($thumbnail, 'id'))->firstOrFail();

            $event->product->thumbnail()->associate($file);
        } else {
            $event->product->thumbnail()->dissociate();
        }
    }
}
