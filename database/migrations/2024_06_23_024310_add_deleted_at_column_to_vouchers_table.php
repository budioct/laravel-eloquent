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
     *
     * Schema::create       : untuk membuat table
     * note: Schema::table  : untuk alter table
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->softDeletes(); // akan generate delete_at

            /**
             * show alter table vouchers,, add column deleted_at
             *
             * alter table vouchers add column `deleted_at` timestamp NULL DEFAULT NULL;
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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
