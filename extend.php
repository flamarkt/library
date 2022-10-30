<?php

namespace Flamarkt\Library;

use ClarkWinkelmann\Mithril2Html\Extend\FrontendNoConflict;
use ClarkWinkelmann\Scout\Extend\Scout;
use Flamarkt\Core\Api\Controller as CoreController;
use Flamarkt\Core\Api\Serializer\BasicProductSerializer;
use Flamarkt\Core\Api\Serializer\ProductSerializer;
use Flamarkt\Core\Product\Event\Saving;
use Flamarkt\Core\Product\Product;
use Flarum\Extend;

$extenders = [
    (new Extend\Frontend('backoffice'))
        ->js(__DIR__ . '/js/dist/backoffice.js')
        ->route('/files', 'files.index')
        ->route('/files/{id:[0-9a-f-]+|new}', 'files.show'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/resources/less/forum.less'),

    (new FrontendNoConflict('mithril2html'))
        ->js(__DIR__ . '/js/dist/mithril2html.js'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Routes('api'))
        ->get('/flamarkt/files', 'flamarkt.files.index', Api\Controller\FileIndexController::class)
        ->post('/flamarkt/files', 'flamarkt.files.store', Api\Controller\FileStoreController::class)
        ->get('/flamarkt/files/{id:[0-9a-f-]+}', 'flamarkt.files.show', Api\Controller\FileShowController::class)
        ->patch('/flamarkt/files/{id:[0-9a-f-]+}', 'flamarkt.files.update', Api\Controller\FileUpdateController::class)
        ->delete('/flamarkt/files/{id:[0-9a-f-]+}', 'flamarkt.files.delete', Api\Controller\FileDeleteController::class),

    (new Extend\Model(Product::class))
        ->belongsToMany('files', File::class, 'flamarkt_file_product')
        ->belongsTo('thumbnail', File::class, 'thumbnail_id'),

    (new Extend\Event())
        ->listen(Saving::class, Listeners\SaveProduct::class),

    (new Extend\ApiSerializer(BasicProductSerializer::class))
        ->hasOne('thumbnail', Api\Serializer\FileSerializer::class),

    (new Extend\ApiSerializer(ProductSerializer::class))
        ->attributes(ProductAttributes::class)
        ->hasMany('files', Api\Serializer\FileSerializer::class),

    (new Extend\ApiController(CoreController\ProductIndexController::class))
        ->addInclude('thumbnail'),
    (new Extend\ApiController(CoreController\ProductShowController::class))
        ->addInclude('thumbnail'),
    (new Extend\ApiController(CoreController\ProductStoreController::class))
        ->addInclude('thumbnail'),
    (new Extend\ApiController(CoreController\ProductUpdateController::class))
        ->addInclude('thumbnail'),
    (new Extend\ApiController(CoreController\OrderShowController::class))
        ->addInclude('lines.product.thumbnail'),

    (new Extend\Filter(FileFilterer::class))
        ->addFilter(Filter\MimeFilter::class),
    (new Extend\SimpleFlarumSearch(FileSearcher::class))
        ->addGambit(Filter\MimeFilter::class)
        ->setFullTextGambit(Filter\FullTextGambit::class),

    (new Extend\ServiceProvider())
        ->register(ImageServiceProvider::class),

    (new Extend\Console())
        ->command(Console\RefreshConversions::class),

    (new Extend\ModelVisibility(File::class))
        ->scope(Scope\View::class),

    (new Extend\Filter(FileFilterer::class))
        ->addFilter(Filter\MimeFilter::class),
];

if (class_exists(Scout::class)) {
    $extenders[] = (new Scout(File::class))
        ->listenSaved(File\Event\Created::class, function (File\Event\Created $event) {
            return $event->file;
        })
        ->listenSaved(File\Event\DescriptionChanged::class, function (File\Event\DescriptionChanged $event) {
            return $event->file;
        })
        ->listenSaved(File\Event\TitleChanged::class, function (File\Event\TitleChanged $event) {
            return $event->file;
        })
        ->listenDeleted(File\Event\Deleted::class, function (File\Event\Deleted $event) {
            return $event->file;
        })
        ->attributes(function (File $file): array {
            return [
                'title' => $file->title,
                'description' => $file->description,
            ];
        });
}

return $extenders;
