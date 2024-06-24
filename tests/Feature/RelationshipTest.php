<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RelationshipTest extends TestCase
{

    /**
     * Relationship
     * ● Di kelas MySQL, kita pernah belajar tentang Relasi antar Tabel, dari mulai One to One, One to
     *   Many dan Many to Many
     * ● Relasi antar tabel tersebut bisa kita lakukan secara manual di Laravel, namun artinya kita harus
     *   melakukan join tabel secara manual
     * ● Untungnya Laravel Eloquent mendukung Model Relationship, sehingga proses join tabel tidak
     *   perlu kita lakukan secara manual
     * ● Kita akan bahas secara bertahap relasi-relasi antar tabel di Laravel Eloquent di materi-materi
     *   berikutnya
     *
     * One to One
     * ● Relasi One to One didukung oleh Laravel Eloquent, dengan cara menggunakan method hasOne()
     *   pada Model
     * ● Kadang-kadang untuk relasi One to One, kita ingin membuat relasi bidirectional antar Model, kita
     *   juga bisa lakukan secara mudah di Laravel Eloquent
     * ● Pada Model kebalikannya, kita bisa menggunakan method belongsTo() pada Model
     *
     * Contoh Kasus
     * ● Sekarang kita akan buat dua model, Customer dan Wallet, dimana satu Customer memiliki satu Wallet
     *
     * // buat model Customer & Wallet
     * // buat migration Customer & Wallet
     * // buat seender Customer & Wallet
     *
     * // set migration
     * // set model
     * // set seender
     */

    public function testQueryOneToOne(){

        // sql: insert into `customers` (`id`, `name`, `email`) values (?, ?, ?)
        $this->seed(CustomerSeeder::class);
        // sql: insert into `wallets` (`amount`, `customer_id`) values (?, ?)
        $this->seed(WalletSeeder::class);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI");

        self::assertNotNull($customer);
        self::assertEquals("BUDHI", $customer->id);
        Log::info(json_encode($customer)); // {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}

        // kita tidak perlu query seperti ini lagi
        //$wallet = Wallet::query()->where("customer_id", $customer->id)->first();

        // ingat bukan memangil methodnya melainkan property nya
        // untuk mengakses model/entity wallet hasil dari query Customer.wallet() seperti $customer.wallet // method wallet() menjadi property
        // select * from `wallets` where `wallets`.`customer_id` = ? and `wallets`.`customer_id` is not null limit 1
        $wallet = $customer->wallet;// akses model wallet dari model customer // hasil adalah object {}

        self::assertNotNull($wallet);
        self::assertEquals("BUDHI", $wallet->customer_id);
        self::assertEquals(7000, $wallet->amount);
        Log::info(json_encode($wallet)); // {"id":1,"customer_id":"BUDHI","amount":7000}

        /**
         * result:
         * [2024-06-23 09:39:29] testing.INFO: insert into `customers` (`id`, `name`, `email`) values (?, ?, ?)
         * [2024-06-23 09:39:29] testing.INFO: insert into `wallets` (`amount`, `customer_id`) values (?, ?)
         * [2024-06-23 09:39:29] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-23 09:39:29] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-23 09:39:29] testing.INFO: select * from `wallets` where `wallets`.`customer_id` = ? and `wallets`.`customer_id` is not null limit 1
         * [2024-06-23 09:39:29] testing.INFO: {"id":1,"customer_id":"BUDHI","amount":7000}
         */

    }



    /**
     * One to Many
     * ● Sekarang kita akan coba implementasi untuk relasi One to Many di Laravel
     * ● Kita akan buat Model Product, dimana berelasi dengan Category
     * ● Satu Category memiliki banyak Product
     * ● Untuk membuat relasi One to Many, hampir sama dengan One to One, yang membedakan adalah
     *   menggunakan method hasMany()
     */

    public function testQueryOneToManyCategory(){

        // sql: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
        // sql: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        // sql: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
        $category = Category::query()->find("FOOD");

        self::assertNotNull($category);
        self::assertEquals("FOOD",$category->id);
        Log::info(json_encode($category));

        // sql: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null
        // $products = Product::query()->where("category_id", "=", $category->id)->get(); // tidak perlu query seperti ini lagi
        $products = $category->products; // akses model product dari model category // hasil adalah list array object [{},{}]

        self::assertNotNull($products);
        self::assertEquals("1",$products[0]->id);
        self::assertEquals("FOOD",$products[0]->category_id);
        Log::info(json_encode($products));

        /**
         * result:
         * [2024-06-23 10:56:36] testing.INFO: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
         * [2024-06-23 10:56:36] testing.INFO: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
         * [2024-06-23 10:56:36] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         * [2024-06-23 10:56:36] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-23 17:56:36","is_active":1}
         * [2024-06-23 10:56:36] testing.INFO: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null
         * [2024-06-23 10:56:36] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}]
         */

    }

    public function testQueryOneToManyProduct(){

        // sql: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
        // sql: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        // sql: select * from `products` where `products`.`id` = ? limit 1
        $product = Product::query()->find("1");

        self::assertNotNull($product);
        self::assertEquals("FOOD",$product->category_id);
        Log::info(json_encode($product));

        // sql: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
        //$category = Category::query()->where("id", "=", $product->category_id)->first(); // tidak perlu query seperti ini lagi
        $category = $product->category; // akses model category dari model product // hasil adalah object {}

        self::assertNotNull($category);
        self::assertEquals("FOOD", $category->id);
        Log::info(json_encode($category));

        /**
         * result:
         * [2024-06-23 11:07:25] testing.INFO: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
         * [2024-06-23 11:07:25] testing.INFO: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
         * [2024-06-23 11:07:25] testing.INFO: select * from `products` where `products`.`id` = ? limit 1
         * [2024-06-23 11:07:25] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}
         * [2024-06-23 11:07:25] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         * [2024-06-23 11:07:25] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-23 18:07:25","is_active":1}
         */

    }


    /**
     * Query Builder pada Relationship
     * ● Semua class relationship di Laravel adalah turunan dari Builder, dari HasOne, HasMany, BelongsTo
     *   sampai BelongsToMany
     * ● Artinya, sebenarnya untuk melakukan proses CRUD, kisa bisa menggunakan method relationship,
     *   untuk mempermudah
     *
     * note: jika kita ingi melakukan save(), delete(), update(), select(), find(), etc..
     * kita gunakan method relasi model dengan return HasOne, HasMany, BelongsTo, karna turunan dari Builder (Eloquent)
     */

    public function testOneToOneQuery(){

        // customers one ~ one vouchers

        $customer = new Customer();
        $customer->id = "BUDHI";
        $customer->name = "budhi";
        $customer->email = "budhi@test.com";

        // sql: insert into `customers` (`id`, `name`, `email`) values (?, ?, ?)
        $customer->save();
        Log::info(json_encode($customer));

        $wallet = new Wallet();
        $wallet->amount = 10000;

        // sql: insert into `wallets` (`amount`, `customer_id`) values (?, ?)
        $customer->wallet()->save($wallet); // ini akan simpan customers.id sebagai FK table vouchers.customer_id
        Log::info(json_encode($wallet));

        self::assertNotNull($wallet->customer_id);
        self::assertEquals("BUDHI",$wallet->customer_id);

        /**
         * result:
         * [2024-06-24 01:12:49] testing.INFO: insert into `customers` (`id`, `name`, `email`) values (?, ?, ?)
         * [2024-06-24 01:12:49] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-24 01:12:49] testing.INFO: insert into `wallets` (`amount`, `customer_id`) values (?, ?)
         * [2024-06-24 01:12:49] testing.INFO: {"amount":10000,"customer_id":"BUDHI","id":7}
         */

    }

    public function testOneToManyQuery()
    {

        // categories one ~ many products

        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = true;

        // sql: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
        $category->save();
        Log::info(json_encode($category));

        $product = new Product();
        $product->id = "1";
        $product->name = "Product 1";
        $product->description = "Description 1";

        // sql: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
        $category->products()->save($product);
        Log::info(json_encode($product));

        self::assertNotNull($product->category_id);
        self::assertEquals("FOOD",$product->category_id);

        /**
         * result:
         * [2024-06-24 01:29:39] testing.INFO: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
         * [2024-06-24 01:29:39] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","is_active":true}
         * [2024-06-24 01:29:39] testing.INFO: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
         * [2024-06-24 01:29:39] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","category_id":"FOOD"}
         */

    }

    public function testRelationshipQuery()
    {
        // sql: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
        // sql: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        // sql: elect * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
        $category = Category::find("FOOD");
        Log::info($category);

        // query jika ingin get all product berdasarkan id categirues
        // sql: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null
        $products = $category->products;
        self::assertCount(1, $products);
        Log::info($products);

        // query untuk mendapatkan data berdasarkan ketentuan kondisi
        // sql: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null and `stock` <= ?
        $outOfStockProducts = $category->products()->where('stock', '<=', 0)->get();
        self::assertCount(1, $outOfStockProducts);
        Log::info($outOfStockProducts);

        /**
         * result:
         * [2024-06-24 01:52:20] testing.INFO: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
         * [2024-06-24 01:52:20] testing.INFO: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
         * [2024-06-24 01:52:20] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         * [2024-06-24 01:52:20] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-24 08:52:20","is_active":1}
         * [2024-06-24 01:52:20] testing.INFO: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null
         * [2024-06-24 01:52:20] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}]
         * [2024-06-24 01:52:20] testing.INFO: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null and `stock` <= ?
         * [2024-06-24 01:52:20] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}]
         */

    }



    /**
     * Has One of Many (mengabil salah satu data dari relasi one to many)
     * ● Saat kita membuat relasi One to Many, kadang kita ingin mendapatkan salah satu data saja pada
     *   relasi One to Many nya
     * ● Contoh pada relasi One to Many Category dan Product, kita ingin mengambil satu product
     *   Termurah atau Termahal di Category tersebut
     * ● Sebenarnya kita bisa lakukan secara manual menggunakan Query Builder
     * ● Namun Laravel menyediakan relasi Has One of Many yang bisa digunakan untuk mempermudah
     *   hal ini
     */


    public function testHashOneOfMany(){

        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        // sql: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
        $category = Category::query()->find("FOOD");
        Log::info($category);

        // sql: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null order by `price` asc limit 1
        $cheapestProduct = $category->cheapestProduct;
        self::assertNotNull($cheapestProduct);
        self::assertEquals("1", $cheapestProduct->id);
        self::assertEquals(0, $cheapestProduct->price);
        Log::info($cheapestProduct);

        // sql: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null order by `price` desc limit 1
        $mostExpensiveProducts = $category->mostExpensiveProducts;
        self::assertNotNull($mostExpensiveProducts);
        self::assertEquals("2", $mostExpensiveProducts->id);
        self::assertEquals(1000, $mostExpensiveProducts->price);
        Log::info($mostExpensiveProducts);

        /**
         * result:
         * [2024-06-24 03:12:26] testing.INFO: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
         * [2024-06-24 03:12:27] testing.INFO: insert into `products` (`id`, `name`, `description`, `category_id`) values (?, ?, ?, ?)
         * [2024-06-24 03:12:27] testing.INFO: insert into `products` (`id`, `name`, `description`, `price`, `category_id`) values (?, ?, ?, ?, ?)
         * [2024-06-24 03:12:27] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         * [2024-06-24 03:12:27] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-24 10:12:26","is_active":1}
         * [2024-06-24 03:12:27] testing.INFO: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null order by `price` asc limit 1
         * [2024-06-24 03:12:27] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}
         * [2024-06-24 03:12:27] testing.INFO: select * from `products` where `products`.`category_id` = ? and `products`.`category_id` is not null order by `price` desc limit 1
         * [2024-06-24 03:12:27] testing.INFO: {"id":"2","name":"Product 2","description":"Description 2","price":1000,"stock":0,"category_id":"FOOD"}
         */

    }



}
