<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\File;
use Flarum\Api\Controller\AbstractDeleteController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class FileDeleteController extends AbstractDeleteController
{
    protected function delete(ServerRequestInterface $request)
    {
        $request->getAttribute('actor')->assertCan('backoffice');

        $id = Arr::get($request->getQueryParams(), 'id');

        $file = File::where('uid', $id)->firstOrFail();

        $file->delete();
    }
}
