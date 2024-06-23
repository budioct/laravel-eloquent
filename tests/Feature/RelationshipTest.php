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

}
