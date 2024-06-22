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
        Schema::create('comments', function (Blueprint $table) {
            // $table->id(); // id() sortcut untuk membuat  $table->bigInteger("id")->autoIncrement()
            // $table->bigInteger("id")->autoIncrement(); // ini yang sebenarnya di jalnkan oleh id()

            $table->integer("id")->autoIncrement(); // autoIncrement() // column id otomatis akan primary_key
            $table->string("email", 100)->nullable(false);
            $table->string("title", 200)->nullable(false);
            $table->text("comment")->nullable(true);
            $table->timestamps(); // akan generate create_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
