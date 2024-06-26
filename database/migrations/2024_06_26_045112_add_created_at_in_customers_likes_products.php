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
        Schema::table('customers_likes_products', function (Blueprint $table) {
            $table->timestamp("created_at")->nullable(false)->useCurrent();

            /**
             * show alter table
             *
             * alter table customers_likes_products add column `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;
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
        Schema::table('customers_likes_products', function (Blueprint $table) {
            $table->dropColumn("created_at");
        });
    }
};
