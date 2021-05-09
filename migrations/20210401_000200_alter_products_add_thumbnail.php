<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->table('flamarkt_products', function (Blueprint $table) {
            $table->unsignedInteger('thumbnail_id')->nullable();

            $table->foreign('thumbnail_id')->references('id')->on('flamarkt_files')->onDelete('set null');
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('flamarkt_products', function (Blueprint $table) {
            $table->dropColumn('thumbnail_id');
        });
    },
];
