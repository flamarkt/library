<?php

namespace Flamarkt\Library;

use Flarum\Foundation\Paths;
use Flarum\User\User;
use Illuminate\Contracts\Queue\Queue;
use Intervention\Image\ImageManager;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileRepository
{
    protected $paths;
    protected $assets;
    protected $queue;

    public function __construct(Paths $paths, FilesystemInterface $assets, Queue $queue)
    {
        $this->paths = $paths;
        $this->assets = $assets;
        $this->queue = $queue;
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

            $file->save();

            $this->queue->push(new CreateConversions($file));

            // Get the latest values in case the conversions were created in a sync job
            return File::query()->findOrFail($file->id);
        } finally {
            @unlink($tmpFile);
        }
    }
}
