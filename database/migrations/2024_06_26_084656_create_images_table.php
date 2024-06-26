<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string("url", 255)->nullable(false);
            $table->string("imageable_id", 100)->nullable(false); // untuk simpan FK dari preimary_key Polymorphic
            $table->string("imageable_type", 200)->nullable(false); // // untuk simpan type Model class Polymorphic
            $table->unique(["imageable_id", "imageable_type"]);

            /**
             * show create table
             *
             * CREATE TABLE `images` (
             * `id` bigint unsigned NOT NULL AUTO_INCREMENT,
             * `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `imageable_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `imageable_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
             * PRIMARY KEY (`id`),
             * UNIQUE KEY `images_imageable_id_imageable_type_unique` (`imageable_id`,`imageable_type`)
             * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
             */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
};
