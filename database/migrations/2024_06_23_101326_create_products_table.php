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
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->nullable(false)->primary();
            $table->string('name')->nullable(false);
            $table->string("description")->nullable();
            $table->integer("price")->nullable(false)->default(0);
            $table->integer("stock")->nullable(false)->default(0);
            $table->string("category_id", 100)->nullable(false);
            $table->foreign("category_id")->references("id")->on("categories");

            /**
             * show create table products
             *
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
        Schema::dropIfExists('products');
    }
};
