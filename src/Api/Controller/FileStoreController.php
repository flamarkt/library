<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\Api\Serializer\FileSerializer;
use Flamarkt\Library\FileRepository;
use Flarum\Api\Controller\AbstractCreateController;
use Flarum\User\User;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Tobscure\JsonApi\Document;

class FileStoreController extends AbstractCreateController
{
    public $serializer = FileSerializer::class;

    protected $repository;

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        /**
         * @var UploadedFileInterface $uploadedFile
         */
        $uploadedFile = Arr::get($request->getUploadedFiles(), 'file');

        /**
         * @var User $actor
         */
        $actor = $request->getAttribute('actor');

        $actor->assertCan('backoffice');

        return $this->repository->store($uploadedFile, $actor);
    }
}
