<?php

namespace Flamarkt\Library\Api\Serializer;

use Flamarkt\Library\File;
use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Http\UrlGenerator;

class FileSerializer extends AbstractSerializer
{
    protected $type = 'flamarkt-files';

    /**
     * @param File $file
     * @return string
     */
    public function getId($file): string
    {
        return $file->uid;
    }

    /**
     * @param File $file
     * @return array
     */
    protected function getDefaultAttributes($file): array
    {
        /**
         * @var UrlGenerator $generator
         */
        $generator = resolve(UrlGenerator::class);

        $attributes = [
            'conversions' => collect($file->conversions)->mapWithKeys(function ($name) use ($file, $generator) {
                return [
                    $name => $generator->to('forum')->path('assets/flamarkt/' . $file->uid . '/' . $name . '.jpg'),
                ];
            })->all(),
            'filename' => $file->filename,
            'title' => $file->title,
            'description' => $file->description,
        ];

        if ($this->actor->can('backoffice')) {
            $attributes += [
                'createdAt' => $this->formatDate($file->created_at),
            ];
        }

        return $attributes;
    }
}
