<?php

namespace Flamarkt\Library;

use Flamarkt\Library\File\Event\Created;
use Flamarkt\Library\File\Event\Saving;
use Flarum\Foundation\DispatchEventsTrait;
use Flarum\Foundation\Paths;
use Flarum\User\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;
use Intervention\Image\ImageManager;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileRepository
{
    use DispatchEventsTrait;

    protected $paths;
    protected $assets;
    protected $queue;

    public function __construct(Paths $paths, FilesystemInterface $assets, Queue $queue, Dispatcher $events)
    {
        $this->paths = $paths;
        $this->assets = $assets;
        $this->queue = $queue;
        $this->events = $events;
    }

    public function store(UploadedFileInterface $uploadedFile, User $actor)
    {
        try {
            $tmpFile = tempnam($this->paths->storage . DIRECTORY_SEPARATOR . 'tmp', 'flamarkt-library');
            $uploadedFile->moveTo($tmpFile);

            $validationFile = new UploadedFile(
                $tmpFile,
                $uploadedFile->getClientFilename(),
                $uploadedFile->getClientMediaType(),
                $uploadedFile->getError(),
                true
            );

            //TODO
            //$validator->assertValid(['file' => $validationFile]);

            $image = (new ImageManager())->make($tmpFile);

            // Then we create the fishes and save the images
            $file = new File();

            $file->uid = Uuid::uuid4()->toString();
            $file->filename = $uploadedFile->getClientFilename();
            $file->title = explode('.', $uploadedFile->getClientFilename())[0];
            $file->user()->associate($actor);

            $this->assets->write($file->uid . '/' . $file->filename, file_get_contents($tmpFile));

            // TODO: pass data to event
            $this->events->dispatch(new Saving($file, $actor, []));

            $file->save();

            $this->dispatchEventsFor($file, $actor);

            $this->events->dispatch(new Created($file, $actor));

            $this->queue->push(new CreateConversions($file));

            // Get the latest values in case the conversions were created in a sync job
            return File::query()->findOrFail($file->id);
        } finally {
            @unlink($tmpFile);
        }
    }
}
