<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\File;
use Flamarkt\Library\File\Event\Deleted;
use Flamarkt\Library\File\Event\Deleting;
use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Http\RequestUtil;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class FileDeleteController extends AbstractDeleteController
{
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    protected function delete(ServerRequestInterface $request)
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertCan('backoffice');

        $id = Arr::get($request->getQueryParams(), 'id');

        $file = File::where('uid', $id)->firstOrFail();

        $this->events->dispatch(new Deleting($file, $actor, $request->getParsedBody()));

        $file->delete();

        $this->events->dispatch(new Deleted($file, $actor));
    }
}
