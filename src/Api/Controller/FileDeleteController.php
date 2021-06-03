<?php

namespace Flamarkt\Library\Api\Controller;

use Flamarkt\Library\File;
use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class FileDeleteController extends AbstractDeleteController
{
    protected function delete(ServerRequestInterface $request)
    {
        RequestUtil::getActor($request)->assertCan('backoffice');

        $id = Arr::get($request->getQueryParams(), 'id');

        $file = File::where('uid', $id)->firstOrFail();

        $file->delete();
    }
}
