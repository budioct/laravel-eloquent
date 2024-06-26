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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid("id")->nullable(false)->primary(); // jadi id adalah uuid dan menjadi primary key
            $table->string("name", 100)->nullable(false);
            $table->string("voucher_code", 200)->nullable(false);
            $table->timestamp("create_at")->nullable(false)->useCurrent(); // jadi create_at akan dibuat setiap kali kita create data baru

            /**
             * show create table vouchers
             *
             * CREATE TABLE `vouchers` (
             * `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `voucher_code` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
        Schema::dropIfExists('vouchers');
    }
};
