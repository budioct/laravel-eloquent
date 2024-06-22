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
