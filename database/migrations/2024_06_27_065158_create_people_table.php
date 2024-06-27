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
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->string("first_name", 100)->nullable(false);
            $table->string("last_name", 100)->nullable();
            $table->timestamps();

            /**
             * show create table
             *
             * CREATE TABLE `person` (
             * `id` bigint unsigned NOT NULL AUTO_INCREMENT,
             * `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
             * `created_at` timestamp NULL DEFAULT NULL,
             * `updated_at` timestamp NULL DEFAULT NULL,
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
        Schema::dropIfExists('person');
    }
};
