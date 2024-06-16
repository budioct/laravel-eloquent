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
