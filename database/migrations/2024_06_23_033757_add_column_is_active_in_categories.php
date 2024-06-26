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
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_active')->default(false); // column is_active ini jika null nilai defaultnya akan di isi false

            /**
             * show alter table categories,, add column is_active
             *
             * alter table vouchers add column `is_active` tinyint(1) NOT NULL DEFAULT '0';
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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropcolumn('is_active');
        });
    }
};
