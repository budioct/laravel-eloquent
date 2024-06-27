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

class QueryRelationTest extends TestCase
{
    /**
     * Querying Relations
     * ● Semua relasi di Laravel sebenarnya adalah turunan dari Query Builder, baik itu Polymorphic
     *   ataupun bukan
     * ● Oleh karena itu, sebenarnya kita bisa melakukan Query seperti yang kita lakukan di Query Builder
     *   pada method relationship di Model yang sudah kita buat
     */

    public function testQueryingRelations(){

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
        // sql: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null and `price` = ?
         $QueryBuilderModel = $category->products()->where("price", "=", 1000)->get(); // result query List object
        //$QueryBuilderModel = $category->products()->where("price", "=", 1000)->firstOrFail(); // result object
        self::assertNotNull($QueryBuilderModel);
        Log::info(json_encode($QueryBuilderModel));

        // contoh query builder standart
        // sql: select * from `products` where `price` = ?
        $queryBuilder = DB::table("products")->where("price", "=", 1000)->get(); // result query List object
        //$queryBuilder = DB::table("products")->where("price", "=", 1000)->first(); // result object
        self::assertNotNull($queryBuilder);
        Log::info(json_encode($queryBuilder));

        /**
         * result:
         * [2024-06-27 04:30:09] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         * [2024-06-27 04:30:09] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-27 11:30:09","is_active":1}
         * [2024-06-27 04:30:09] testing.INFO: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null and `price` = ?
         * [2024-06-27 04:30:09] testing.INFO: [{"id":"2","name":"Product 2","description":"Description 2","price":1000,"stock":0,"category_id":"FOOD"}]
         * [2024-06-27 04:30:09] testing.INFO: select * from `products` where `price` = ?
         * [2024-06-27 04:30:09] testing.INFO: [{"id":"2","name":"Product 2","description":"Description 2","price":1000,"stock":0,"category_id":"FOOD"}]
         */

    }

}
