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
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->integerIncrements("id")->nullable(false);
            $table->unsignedInteger("wallet_id")->nullable(false);
            $table->string("bank", 100)->nullable(false);
            $table->string("va_number", 100)->nullable(false);
            $table->foreign("wallet_id")->on("wallets")->references("id"); // CONSTRAINT `virtual_accounts_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`)

            /**
             * show create table virtual_accounts
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
        Schema::dropIfExists('virtual_accounts');
    }
};
