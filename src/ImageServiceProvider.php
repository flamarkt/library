<?php

namespace Flamarkt\Library;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Foundation\Paths;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

class ImageServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $constraint = function (Constraint $size) {
            $size->upsize();
        };

        $this->container->instance('flamarkt.library.conversions', [
            '150x150' => function (Image $image) use ($constraint): ?Image {
                return $image->fit(150, 150, $constraint);
            },
            '400' => function (Image $image) use ($constraint): ?Image {
                if ($image->width() > $image->height()) {
                    return $image->widen(400, $constraint);
                } else {
                    return $image->heighten(400, $constraint);
                }
            },
            '800' => function (Image $image) use ($constraint): ?Image {
                // If the image is smaller than the previous thumbnail size, there's no reason to create this one
                if ($image->width() <= 400 && $image->height() <= 400) {
                    return null;
                }

                if ($image->width() > $image->height()) {
                    return $image->widen(800, $constraint);
                } else {
                    return $image->heighten(800, $constraint);
                }
            },
        ]);

        $this->container->bind('flamarkt.library.filesystem', function () {
            /**
             * @var Paths $paths
             */
            $paths = $this->container->make(Paths::class);

            return new Filesystem(new Local($paths->public . DIRECTORY_SEPARATOR . 'assets/flamarkt'));
        });

        $this->container->when(ConversionRepository::class)
            ->needs(FilesystemInterface::class)
            ->give('flamarkt.library.filesystem');
        $this->container->when(FileRepository::class)
            ->needs(FilesystemInterface::class)
            ->give('flamarkt.library.filesystem');
    }
}
