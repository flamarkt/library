<?php

namespace Flamarkt\Library\Extend;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Illuminate\Contracts\Container\Container;

class File implements ExtenderInterface
{
    protected $conversions = [];

    public function conversion(string $name, callable $callback)
    {
        $this->conversions[$name] = $callback;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->extend('flamarkt.library.conversions', function (array $conversions): array {
            return array_merge($conversions, $this->conversions);
        });
    }
}
