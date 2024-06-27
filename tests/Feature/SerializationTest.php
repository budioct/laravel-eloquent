<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SerializationTest extends TestCase
{
    /**
     * Serialization
     * ● Saat kita membuat RESTful API, kita sering sekali melakukan konversi data Model menjadi JSON
     *   atau Array
     * ● Eloquent sudah menyediakan method untuk melakukan konversi tersebut menggunakan method
     *   toArray() dan toJSON()
     * ● Secara otomatis Model akan dikonversi, dan semua kolom akan di include
     * ● Termasuk Eloquent Collection pun bisa kita konversi menjadi Array dan JSON
     */

    public function testSerialization(){

        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        // sql: select * from `products`
        $products = Product::query()->get();
        self::assertNotNull($products);
        self::assertCount(2,$products);

        $json = $products->toJson(JSON_PRETTY_PRINT); // toJson(optionas) // konversi model ke bentuk JSON
        Log::info($json);

        /**
         * result:
         * [2024-06-27 09:21:46] testing.INFO: select * from `products`
         * [2024-06-27 09:21:46] testing.INFO: [
         * {
         * "id": "1",
         * "name": "Product 1",
         * "description": "Description 1",
         * "price": 0,
         * "stock": 0,
         * "category_id": "FOOD"
         * },
         * {
         * "id": "2",
         * "name": "Product 2",
         * "description": "Description 2",
         * "price": 1000,
         * "stock": 0,
         * "category_id": "FOOD"
         * }
         * ]
         */

    }

    /**
     * Relationship
     * ● Secara default, relasi yang belum di load, tidak akan di include ke dalam proses Serialization
     * ● Jika kita ingin, kita harus load data relasinya terlebih dahulu
     */

    public function testSerializationLoadRelasi(){

        // table products beralasi dengan table categories.. many to one
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        // sql: select * from `products`
        $products = Product::query()->get();

        // sql select * from `categories` where `categories`.`id` in (?) and `is_active` = ?
        $products->load("category"); // load($relations): Collection // akan mencakup data yang berelasi dengan model Product yaitu Category

        self::assertNotNull($products);
        self::assertCount(2,$products);

        $json = $products->toJson(JSON_PRETTY_PRINT); // toJson(optionas) // konversi model ke bentuk JSON
        Log::info($json);

        /**
         * result:
         * [2024-06-27 09:32:29] testing.INFO: select * from `products`
         * [2024-06-27 09:32:29] testing.INFO: select * from `categories` where `categories`.`id` in (?) and `is_active` = ?
         * [2024-06-27 09:32:29] testing.INFO: [
         * {
         * "id": "1",
         * "name": "Product 1",
         * "description": "Description 1",
         * "price": 0,
         * "stock": 0,
         * "category_id": "FOOD",
         * "category": {
         * "id": "FOOD",
         * "name": "Food",
         * "description": "Food Category",
         * "created_at": "2024-06-27 16:32:29",
         * "is_active": 1
         * }
         * },
         * {
         * "id": "2",
         * "name": "Product 2",
         * "description": "Description 2",
         * "price": 1000,
         * "stock": 0,
         * "category_id": "FOOD",
         * "category": {
         * "id": "FOOD",
         * "name": "Food",
         * "description": "Food Category",
         * "created_at": "2024-06-27 16:32:29",
         * "is_active": 1
         * }
         * }
         * ]
         */

    }

    /**
     * Hidden Attributes
     * ● Kadang, beberapa attribute tidak ingin kita tampilkan dalam proses Serialization
     * ● Kita bisa menambahkan attributes mana saja yang mau kita hilangkan pada proses Serialization di property $hidden
     */

    public function testSerializationLoadRelasiHiddenAttributes()
    {

        // table products beralasi dengan table categories.. many to one
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        // sql: select * from `products`
        $products = Product::query()->get();

        // sql select * from `categories` where `categories`.`id` in (?) and `is_active` = ?
        $products->load("category"); // load($relations): Collection // akan mencakup data yang berelasi dengan model Product yaitu Category

        self::assertNotNull($products);
        self::assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT); // toJson(optionas) // konversi model ke bentuk JSON
        Log::info($json);

        /**
         * result: attribute / column "category_id" sudah di hidden ketika model Product di query/ table products di akses
         * [2024-06-27 09:35:37] testing.INFO: select * from `products`
         * [2024-06-27 09:35:37] testing.INFO: select * from `categories` where `categories`.`id` in (?) and `is_active` = ?
         * [2024-06-27 09:35:37] testing.INFO: [
         * {
         * "id": "1",
         * "name": "Product 1",
         * "description": "Description 1",
         * "price": 0,
         * "stock": 0,
         * "category": {
         * "id": "FOOD",
         * "name": "Food",
         * "description": "Food Category",
         * "created_at": "2024-06-27 16:35:37",
         * "is_active": 1
         * }
         * },
         * {
         * "id": "2",
         * "name": "Product 2",
         * "description": "Description 2",
         * "price": 1000,
         * "stock": 0,
         * "category": {
         * "id": "FOOD",
         * "name": "Food",
         * "description": "Food Category",
         * "created_at": "2024-06-27 16:35:37",
         * "is_active": 1
         * }
         * }
         * ]
         */

    }

    /**
     * Date Format
     * ● Secara default, tipe data Date/Time akan ditampilkan dalam format String
     * ● Kadang, kita ingin mengubah formatnya, pada kasus ini, kita bisa menambahkan informasi date format pada $casts
     * ● Date Format bisa kita pelajari di https://www.php.net/manual/en/datetime.format.php
     */

    public function testSerializationLoadRelasiHiddenAndCastingDateFormatAttributes()
    {

        // table products beralasi dengan table categories.. many to one
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        // sql: select * from `products`
        $products = Product::query()->get();

        // sql select * from `categories` where `categories`.`id` in (?) and `is_active` = ?
        $products->load("category"); // load($relations): Collection // akan mencakup data yang berelasi dengan model Product yaitu Category

        self::assertNotNull($products);
        self::assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT); // toJson(optionas) // konversi model ke bentuk JSON
        Log::info($json);

        /**
         * result:
         * // attribute / column "category_id" sudah di hidden ketika model Product di query/ table products di akses
         * // attribute / column "created_at" akan di rubah menjadi Detik sejak the Unix Epoch (January 1 1970 00:00:00 GMT)
         * [2024-06-27 09:44:30] testing.INFO: select * from `products`
         * [2024-06-27 09:44:30] testing.INFO: select * from `categories` where `categories`.`id` in (?) and `is_active` = ?
         * [2024-06-27 09:44:30] testing.INFO: [
         * {
         * "id": "1",
         * "name": "Product 1",
         * "description": "Description 1",
         * "price": 0,
         * "stock": 0,
         * "category": {
         * "id": "FOOD",
         * "name": "Food",
         * "description": "Food Category",
         * "created_at": "1719506670",
         * "is_active": 1
         * }
         * },
         * {
         * "id": "2",
         * "name": "Product 2",
         * "description": "Description 2",
         * "price": 1000,
         * "stock": 0,
         * "category": {
         * "id": "FOOD",
         * "name": "Food",
         * "description": "Food Category",
         * "created_at": "1719506670",
         * "is_active": 1
         * }
         * }
         * ]
         *
         */

    }

}
