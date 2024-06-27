<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EloquentCollectionTest extends TestCase
{
    /**
     * Eloquent Collection
     * ● Saat kita mengambil data dari database menggunakan Eloquent, semua hasilnya adalah Collection
     *   (yang sudah kita pelajari di kelas Laravel Collection)
     * ● Namun khusus dari Eloquent, sebenarnya hasil nya itu bukan class Collection
     * ● https://laravel.com/api/10.x/Illuminate/Support/Collection.html
     * ● Melainkan class Collection di Eloquent
     * ● https://laravel.com/api/10.x/Illuminate/Database/Eloquent/Collection.html
     * ● Namun Eloquent Collection adalah class turunan dari Laravel Collection, oleh karena itu semua
     *   fitur tetap bisa kita gunakan
     *
     * Eloquent Collection Method
     * ● Namun terdapat banyak tambahan pada Eloquent Collection yang bisa kita gunakan
     * ● https://laravel.com/docs/10.x/eloquent-collections#available-methods
     */

    public function testEloquentCollection()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        // query eloquent standart
        // sql: select * from `products`
        $products = Product::query()->get();
        self::assertNotNull($products);
        self::assertCount(2, $products);

        // contoh Eloquent Collection "toQuery()" Dapatkan pembuat kueri Eloquent dari collection.
        // sql: select * from `products` where `products`.`id` in (?, ?) and `price` = ?
        $list = $products->toQuery()->where("price", "=", 1000)->get();
        self::assertNotNull($list);
        Log::info(json_encode($list));

        // sql: select * from `products` where `products`.`id` in (?, ?) and `price` = ? limit 1
        $object = $products->toQuery()->where("price", "=", 1000)->first();
        self::assertNotNull($object);
        Log::info(json_encode($object));


        /**
         * result:
         * [2024-06-27 06:46:16] testing.INFO: select * from `products`
         * [2024-06-27 06:46:16] testing.INFO: select * from `products` where `products`.`id` in (?, ?) and `price` = ?
         * [2024-06-27 06:46:16] testing.INFO: [{"id":"2","name":"Product 2","description":"Description 2","price":1000,"stock":0,"category_id":"FOOD"}]
         * [2024-06-27 06:46:16] testing.INFO: select * from `products` where `products`.`id` in (?, ?) and `price` = ? limit 1
         * [2024-06-27 06:46:16] testing.INFO: {"id":"2","name":"Product 2","description":"Description 2","price":1000,"stock":0,"category_id":"FOOD"}
         */

    }

}
