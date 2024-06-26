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
        Schema::create('customers_likes_products', function (Blueprint $table) {
            $table->string("customer_id", 100)->nullable(false);
            $table->string("product_id", 100)->nullable(false);
            $table->primary(["customer_id", "product_id"]);
            $table->foreign("customer_id")->on("customers")->references("id");
            $table->foreign("product_id")->on("products")->references("id");

            /**
             * show create table pivot
             * CREATE TABLE `customers_likes_products` (
             * `customer_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * `product_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
             * PRIMARY KEY (`customer_id`,`product_id`),
             * KEY `customers_likes_products_product_id_foreign` (`product_id`),
             * CONSTRAINT `customers_likes_products_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
             * CONSTRAINT `customers_likes_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
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
        Schema::dropIfExists('customers_likes_products');
    }
};
