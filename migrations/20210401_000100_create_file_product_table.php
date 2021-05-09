<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->create('flamarkt_file_product', function (Blueprint $table) {
            $table->unsignedInteger('file_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('order')->nullable();
            $table->timestamps();

            $table->foreign('file_id')->references('id')->on('flamarkt_files')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('flamarkt_products')->onDelete('cascade');
        });
    },
    'down' => function (Builder $schema) {
        $schema->dropIfExists('flamarkt_file_product');
    },
];
