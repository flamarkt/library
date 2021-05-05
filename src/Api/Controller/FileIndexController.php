<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\Api\Serializer\FileSerializer;
use Flamarkt\Library\File;
use Flarum\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class FileIndexController extends AbstractListController
{
    public $serializer = FileSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $request->getAttribute('actor')->assertCan('backoffice');

        return File::all();
    }
}
