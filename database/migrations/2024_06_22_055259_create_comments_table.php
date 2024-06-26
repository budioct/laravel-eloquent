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
        Schema::create('comments', function (Blueprint $table) {
            // $table->id(); // id() sortcut untuk membuat  $table->bigInteger("id")->autoIncrement()
            // $table->bigInteger("id")->autoIncrement(); // ini yang sebenarnya di jalnkan oleh id()

            $table->integer("id")->autoIncrement(); // autoIncrement() // column id otomatis akan primary_key
            $table->string("email", 100)->nullable(false);
            $table->string("title", 200)->nullable(false);
            $table->text("comment")->nullable(true);
            $table->timestamps(); // akan generate create_at dan updated_at

            /**
             * show create table comments
             *
             * CREATE TABLE `comments` (
             * `id` int NOT NULL AUTO_INCREMENT,
             * `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `comment` text COLLATE utf8mb4_unicode_ci,
             * `created_at` timestamp NULL DEFAULT NULL,
             * `updated_at` timestamp NULL DEFAULT NULL,
             * PRIMARY KEY (`id`)
             * ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
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
        Schema::dropIfExists('comments');
    }
};
