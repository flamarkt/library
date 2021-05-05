<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\Api\Serializer\FileSerializer;
use Flamarkt\Library\File;
use Flarum\Api\Controller\AbstractShowController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class FileShowController extends AbstractShowController
{
    public $serializer = FileSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $request->getAttribute('actor')->assertCan('backoffice');

        $id = Arr::get($request->getQueryParams(), 'id');

        return File::where('uid', $id)->firstOrFail();
    }
}
