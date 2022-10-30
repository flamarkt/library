<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\Api\Serializer\FileSerializer;
use Flamarkt\Library\File;
use Flamarkt\Library\File\Event\DescriptionChanged;
use Flamarkt\Library\File\Event\Saving;
use Flamarkt\Library\File\Event\TitleChanged;
use Flarum\Api\Controller\AbstractShowController;
use Flarum\Foundation\DispatchEventsTrait;
use Flarum\Http\RequestUtil;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class FileUpdateController extends AbstractShowController
{
    use DispatchEventsTrait;

    public $serializer = FileSerializer::class;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertCan('backoffice');

        $id = Arr::get($request->getQueryParams(), 'id');

        /**
         * @var File $file
         */
        $file = File::where('uid', $id)->firstOrFail();

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        //TODO: validator

        if (Arr::exists($attributes, 'title')) {
            $newTitle = trim(Arr::get($attributes, 'title'));
            $oldTitle = $file->title;

            if ($newTitle !== $oldTitle) {
                $file->title = $newTitle;
                $file->raise(new TitleChanged($file, $oldTitle));
            }
        }

        if (Arr::exists($attributes, 'description')) {
            $newDescription = trim(Arr::get($attributes, 'description'));
            $oldDescription = $file->description;

            if ($newDescription !== $oldDescription) {
                $file->description = $newDescription;
                $file->raise(new DescriptionChanged($file, $oldDescription));
            }
        }

        $this->events->dispatch(new Saving($file, $actor, $request->getParsedBody()));

        $file->save();

        $this->dispatchEventsFor($file, $actor);

        return $file;
    }
}
