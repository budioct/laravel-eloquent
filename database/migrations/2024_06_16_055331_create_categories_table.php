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
        Schema::create('categories', function (Blueprint $table) {
            $table->string("id", 100)->nullable(false)->primary(); // primary() // akan primary key
            $table->string("name", 100)->nullable(false);
            $table->text("description")->nullable(false);
            $table->timestamp("created_at")->nullable(false)->useCurrent(); // useCurrent // akan menggunakan auto currentDate pada mysql tanpa harus set data

            /**
             * show create table categories
             *
             * CREATE TABLE `categories` (
             * `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `description` text COLLATE utf8mb4_unicode_ci,
             * `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             * PRIMARY KEY (`id`)
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
        Schema::dropIfExists('categories');
    }
};
