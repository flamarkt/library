<?php

namespace Flamarkt\Library;

use Flamarkt\Core\Api\Serializer\BasicProductSerializer;
use Flamarkt\Core\Api\Serializer\ProductSerializer;
use Flamarkt\Core\Product\Event\Saving;
use Flamarkt\Core\Product\Product;
use Flamarkt\Core\Product\ProductFilterer;
use Flamarkt\Core\Product\ProductSearcher;
use Flarum\Extend;

return [
    (new Extend\Frontend('backoffice'))
        ->js(__DIR__ . '/js/dist/backoffice.js')
        ->route('/files', 'files.index')
        ->route('/files/{id:[0-9a-f-]+|new}', 'files.show'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Routes('api'))
        ->get('/flamarkt/files', 'flamarkt.files.index', Api\Controller\FileIndexController::class)
        ->post('/flamarkt/files', 'flamarkt.files.store', Api\Controller\FileStoreController::class)
        ->get('/flamarkt/files/{id:[0-9a-f-]+}', 'flamarkt.files.show', Api\Controller\FileShowController::class)
        ->patch('/flamarkt/files/{id:[0-9a-f-]+}', 'flamarkt.files.update', Api\Controller\FileUpdateController::class)
        ->delete('/flamarkt/files/{id:[0-9a-f-]+}', 'flamarkt.files.delete', Api\Controller\FileDeleteController::class),

    (new Extend\Model(Product::class))
        ->belongsToMany('files', File::class, 'flamarkt_file_product')
        ->hasOne('thumbnail', File::class, 'flamarkt_file_product'),//TODO

    (new Extend\Event())
        ->listen(Saving::class, Listeners\SaveProduct::class),

    (new Extend\ApiSerializer(BasicProductSerializer::class))
        ->hasOne('thumbnail', Api\Serializer\FileSerializer::class),

    (new Extend\ApiSerializer(ProductSerializer::class))
        ->attributes(ProductAttributes::class)
        ->hasMany('files', Api\Serializer\FileSerializer::class),

    /*(new Extend\Filter(ProductFilterer::class))
        ->addFilter(Gambit\ProductCategoryGambit::class),
    (new Extend\SimpleFlarumSearch(ProductSearcher::class))
        ->addGambit(Gambit\ProductCategoryGambit::class),*/

    (new Extend\ServiceProvider())
        ->register(ImageServiceProvider::class),

    (new Extend\Console())
        ->command(Console\RefreshConversions::class),
];
