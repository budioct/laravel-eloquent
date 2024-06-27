<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AggregatingRelationTest extends TestCase
{
    /**
     * Aggregating Relations
     * ● Karena Relation di Laravel adalah Query Builder, jadi kita juga bisa melakukan Aggregate Query di Relation
     * ● Bisa kita lakukan seperti yang biasa kita lakukan di Query Builder
     */

    public function testQueryingRelationsAggregate(){

        // kita bisa menggunakan query builder di model eloquent laravel

        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        // sql: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
        $category = Category::query()->find("FOOD");
        self::assertNotNull($category);
        Log::info(json_encode($category));

        // contoh query builder dengan model
        // sql:
        $QueryBuilderModel = $category->products()->count(); // result int jumlah total data relasi table products
        self::assertNotNull($QueryBuilderModel);
        self::assertEquals(2, $QueryBuilderModel);
        Log::info(json_encode($QueryBuilderModel));

        // aggregat dengan kondisi
        // sql:
        $QueryBuilderModelConditional = $category->products()->where("price", "=", 1000)->count(); // result int jumlah total data relasi table products
        self::assertNotNull($QueryBuilderModelConditional);
        self::assertEquals(1, $QueryBuilderModelConditional);
        Log::info(json_encode($QueryBuilderModelConditional));

        // contoh query builder standart
        // sql:
        $queryBuilder = DB::table("products")->count(); // result int jumlah total data relasi
        //$queryBuilder = DB::table("products")->where("price", "=", 1000)->count(); // result int jumlah total data relasi
        self::assertNotNull($queryBuilder);
        Log::info(json_encode($queryBuilder));

        /**
         * result:
         * [2024-06-27 04:44:17] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         * [2024-06-27 04:44:17] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-27 11:44:17","is_active":1}
         * [2024-06-27 04:44:17] testing.INFO: select count(*) as aggregate from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null
         * [2024-06-27 04:44:17] testing.INFO: 2
         * [2024-06-27 04:44:17] testing.INFO: select count(*) as aggregate from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null and `price` = ?
         * [2024-06-27 04:44:17] testing.INFO: 1
         * [2024-06-27 04:44:17] testing.INFO: select count(*) as aggregate from `products`
         * [2024-06-27 04:44:17] testing.INFO: 2
         */

    }
}
