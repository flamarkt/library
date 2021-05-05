<?php

namespace Flamarkt\Library;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use League\Flysystem\FilesystemInterface;

class ConversionRepository
{
    protected $assets;

    public function __construct(FilesystemInterface $assets)
    {
        $this->assets = $assets;
    }

    protected function createPath(File $file, string $filename): string
    {
        return $file->uid . '/' . $filename;
    }

    protected function deleteAll(File $file)
    {
        foreach ($file->conversions as $name) {
            $path = $this->createPath($file, $name . '.jpg');

            if ($this->assets->has($path)) {
                $this->assets->delete($path);
            }
        }
    }

    protected function createAll(File $file)
    {
        $image = (new ImageManager())->make($this->assets->readStream($this->createPath($file, $file->filename)));

        if (extension_loaded('exif')) {
            $image->orientate();
        }

        $conversionInfo = [];

        foreach (resolve('flamarkt.library.conversions') as $name => $callback) {
            /**
             * @var Image $encoded
             */
            $encoded = $callback(clone $image)->encode('jpg');

            $this->assets->put($this->createPath($file, $name . '.jpg'), $encoded);

            $conversionInfo[] = $name;
        }

        $file->conversions = $conversionInfo;
    }

    public function regenerate(File $file)
    {
        $this->deleteAll($file);
        $this->createAll($file);

        $file->save();
    }
}
