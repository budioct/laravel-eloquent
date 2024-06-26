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
        Schema::table('comments', function (Blueprint $table) {
            $table->string("commentable_id", 100)->nullable(false)->after("comment");
            $table->string("commentable_type", 200)->nullable(false)->after("commentable_id");

            /**
             * show alter table
             *
             * alter table comments add column `commentable_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL;
             * alter table comments add column `commentable_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL;
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(["commentable_id", "commentable_type"]);
        });
    }
};
