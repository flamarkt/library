<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\Api\Serializer\FileSerializer;
use Flamarkt\Library\File;
use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class FileUpdateController extends AbstractShowController
{
    public $serializer = FileSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        RequestUtil::getActor($request)->assertCan('backoffice');

        $id = Arr::get($request->getQueryParams(), 'id');

        /**
         * @var File $file
         */
        $file = File::where('uid', $id)->firstOrFail();

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        //TODO: validator

        if (Arr::exists($attributes, 'title')) {
            $file->title = Arr::get($attributes, 'title');
        }

        if (Arr::exists($attributes, 'description')) {
            $file->description = Arr::get($attributes, 'description');
        }

        $file->save();

        return $file;
    }
}
