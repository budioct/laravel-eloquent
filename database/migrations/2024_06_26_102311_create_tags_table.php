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
        Schema::create('tags', function (Blueprint $table) {
            $table->string("id", 100)->nullable(false)->primary();
            $table->string("name", 100)->nullable(false);

            /**
             * show create table
             *
             * CREATE TABLE `tags` (
             * `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * PRIMARY KEY (`id`)
             * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
             */
        });

        // untuk table pivot polymorphic
        Schema::create('taggables', function (Blueprint $table) {
            $table->string("tag_id", 100)->nullable(false);
            $table->string("taggable_id", 100)->nullable(false);
            $table->string("taggable_type", 100)->nullable(false);
            $table->primary(["tag_id", "taggable_id", "taggable_type"]);

            /**
             * show create table
             *
             * CREATE TABLE `taggables` (
             * `tag_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `taggable_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `taggable_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * PRIMARY KEY (`tag_id`,`taggable_id`,`taggable_type`)
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
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
    }
};
