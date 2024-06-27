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
        Schema::create('employees', function (Blueprint $table) {
            $table->string("id", 100)->nullable(false)->primary();
            $table->string("name", 100)->nullable(false);
            $table->string("title", 100)->nullable(false);
            $table->bigInteger("salary")->nullable(false);
            $table->timestamps();

            /**
             * show create table
             *
             *CREATE TABLE `employees` (
             * `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `salary` bigint NOT NULL,
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
        Schema::dropIfExists('employees');
    }
};
