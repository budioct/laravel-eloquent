<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\VirtualAccountSeeder;
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




    /**
     * Has One Through
     * ● Saat kita membuat relasi One to One, kadang kita buat relasi One to One yang melewati Lebih dari
     *   satu Model
     * ● Contoh, Customer punya satu Wallet, dan Wallet punya satu Virtual Account
     * ● Kita bisa membuat relasi Customer ke Virtual Account, dengan relasi One to One yang melewati
     *   Model Wallet
     */

    public function testHasOneThrough(){

        $this->seed([
            CustomerSeeder::class,
            WalletSeeder::class,
            VirtualAccountSeeder::class
        ]);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI");

        self::assertNotNull($customer);
        self::assertEquals("budhi",$customer->name);

        // dari customers langsung ke virtual_accounts
        // sql: select `virtual_accounts`.*, `wallets`.`customer_id` as `laravel_through_key` from `virtual_accounts` inner join `wallets` on `wallets`.`id` = `virtual_accounts`.`wallet_id` where `wallets`.`customer_id` = ? limit 1
        $virtualAccount = $customer->virtualAccount;
        self::assertNotNull($virtualAccount);
        self::assertEquals("BCA", $virtualAccount->bank);
        self::assertEquals("2222333344", $virtualAccount->va_number);

        /**
         * result:
         * [2024-06-24 10:00:18] testing.INFO: insert into `customers` (`id`, `name`, `email`) values (?, ?, ?)
         * [2024-06-24 10:00:18] testing.INFO: insert into `wallets` (`amount`, `customer_id`) values (?, ?)
         * [2024-06-24 10:00:18] testing.INFO: select * from `wallets` where `customer_id` = ? limit 1
         * [2024-06-24 10:00:18] testing.INFO: insert into `virtual_accounts` (`bank`, `va_number`, `wallet_id`) values (?, ?, ?)
         * [2024-06-24 10:00:18] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-24 10:00:18] testing.INFO: select `virtual_accounts`.*, `wallets`.`customer_id` as `laravel_through_key` from `virtual_accounts` inner join `wallets` on `wallets`.`id` = `virtual_accounts`.`wallet_id` where `wallets`.`customer_id` = ? limit 1
         */

    }




    /**
     * Has Many Through
     * ● Selain Has One Through, Laravel juga bisa digunakan untuk mengambil relasi One to Many melalui
     *   Model lain
     * ● Contoh misal pada kasus kita, kita punya model Category yang berelasi One to Many dengan
     *   Product. Misal Product memiliki relasi One to Many lagi ke model Review
     * ● Kita bisa membuat relasi One to Many dari Category ke Review melewati model Product
     */

    public function testHasManyThrough(){

        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            ReviewSeeder::class
        ]);

        // sql: testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
        $category = Category::query()->find("FOOD");
        self::assertNotNull($category);
        Log::info(json_encode($category));

        // dari categories langsung ke reviews
        // sql: select `reviews`.*, `products`.`category_id` as `laravel_through_key` from `reviews` inner join `products` on `products`.`id` = `reviews`.`product_id` where `products`.`category_id` = ?
        $reviews = $category->reviews;
        self::assertNotNull($reviews);
        self::assertCount(2, $reviews);
        Log::info(json_encode($reviews));

        /**
         * result:
         * [2024-06-24 12:59:06] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         * [2024-06-24 12:59:06] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-24 19:59:06","is_active":1}
         * [2024-06-24 12:59:06] testing.INFO: select `reviews`.*, `products`.`category_id` as `laravel_through_key` from `reviews` inner join `products` on `products`.`id` = `reviews`.`product_id` where `products`.`category_id` = ?
         * [2024-06-24 12:59:06] testing.INFO: [{"id":17,"product_id":"1","customer_id":"BUDHI","rating":5,"comment":"Bagus Banget","laravel_through_key":"FOOD"},{"id":18,"product_id":"2","customer_id":"BUDHI","rating":3,"comment":"Lumayan","laravel_through_key":"FOOD"}]
         */

    }




    /**
     * Many to Many
     * ● Seperti yang kita tahu, relasi Many to Many harus membuat tabel jembatan di tengahnya
     * ● Dan ketika implementasi relasi Many to Many di Model, cukup mudah, cukup gunakan
     *   belongsToMany di kedua Model nya
     * ● Misal kita akan membuat fitur Likes, dimana Customer bisa melakukan Likes ke Product, yang
     *   artinya satu Customer bisa Likes banyak Product, satu Product bisa di Likes oleh banyak Customer
     * ● Artinya relasinya adalah Many to Many
     * ● Kita akan buat tabel customers_likes_products sebagai tabel jembatan nya
     *
     * Menambah Relasi
     * ● Karena pada kasus Many to Many, kita tidak memiliki Model untuk tabel jembatannya, oleh karena
     *   itu untuk menambah relasi kita tidak bisa melakukan insert data Model pertama atau Model kedua
     * ● Untuk menambah relasi, kita bisa menggunakan method relation BelongsToMany dengan
     *   mengunakan method attach()
     */

    public function testInsertManyToMany(){

        $this->seed([
            CustomerSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class
        ]);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI");
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        // sql: insert into `customers_likes_products` (`customer_id`, `product_id`) values (?, ?)
        $customer->likeProducts()->attach("1"); // attach("id relasi pivot")menambah data products pada relasi table pivot
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        // sql: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
        $products = $customer->likeProducts; // akses table pivot
        self::assertCount(1, $products);
        self::assertEquals("1", $products[0]->id);
        Log::info(json_encode($products));

        // insert sebaliknya
//        $product = Product::query()->find("2");
//        self::assertNotNull($product);
//        Log::info(json_encode($product));

//        $product->likeByCustomers()->attach("BUDHI"); // attach("id relasi pivot") // tambah data ke table pivot
//        self::assertNotNull($product);
//        Log::info(json_encode($product));

//        $customers = $product->likeByCustomers; // akses table pivot
//        self::assertCount(1, $customers);
//        self::assertEquals("BUDHI", $customers[0]->id);
//        Log::info(json_encode($customers));

        /**
         * result:
         * [2024-06-26 03:06:11] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 03:06:11] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-26 03:06:11] testing.INFO: insert into `customers_likes_products` (`customer_id`, `product_id`) values (?, ?)
         * [2024-06-26 03:06:11] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-26 03:06:11] testing.INFO: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
         * [2024-06-26 03:06:11] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD","pivot":{"customer_id":"BUDHI","product_id":"1"}}]
         */

    }

    public function testQueryManyToMany(){

        $this->testInsertManyToMany();

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI");
        Log::info(json_encode($customer));

        // sql: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
        $products = $customer->likeProducts;
        Log::info(json_encode($products));

        self::assertNotNull($products);
        self::assertCount(1, $products);
        self::assertEquals("1", $products[0]->id);
        self::assertEquals("Product 1", $products[0]->name);
        self::assertEquals("Description 1", $products[0]->description);

        /**
         * [2024-06-26 03:27:40] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 03:27:40] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-26 03:27:40] testing.INFO: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
         * [2024-06-26 03:27:40] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD","pivot":{"customer_id":"BUDHI","product_id":"1"}}]
         */

    }

    /**
     * Menghapus Relasi
     * ● Untuk menghapus relasi One to One atau One to Many cukup mudah, tinggal menghapus data
     *   kolom FK nya
     * ● Untuk menghapus data relasi Many to Many, kita bisa menggunakan method detach() pada
     *   BelongsToMany
     */

    public function testRemoveManyToMany(){

        $this->testInsertManyToMany();

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI");

        // sql: delete from `customers_likes_products` where `customers_likes_products`.`customer_id` = ? and `customers_likes_products`.`product_id` in (?)
        $customer->likeProducts()->detach("1"); // detach("id relasi pivot") //  hapus data products pada relasi table pivot

        // sql: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
        $products = $customer->likeProducts;
        self::assertNotNull($products);
        self::assertCount(0,$products);
        Log::info(json_encode($products));

        /**
         * result:
         * [2024-06-26 03:32:48] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 03:32:48] testing.INFO: delete from `customers_likes_products` where `customers_likes_products`.`customer_id` = ? and `customers_likes_products`.`product_id` in (?)
         * [2024-06-26 03:32:48] testing.INFO: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
         * [2024-06-26 03:32:48] testing.INFO: []
         */

    }




    /**
     * Intermediate Table (pivot)
     * ● Tabel penghubung (pivot) untuk relasi Many to Many kita sebut dengan Intermediate Table
     * ● Kadang, pada kasus tertentu, tabel tersebut tidak hanya berisikan dua kolom (FK Model 1 dan FK
     *   Model 2), kadang pada kasus tertentu, terdapat kolom tambahan
     * ● Misal created_at, berisikan waktu relasi tersebut dibuat
     *
     * // buat migration add column pada table pivot many to many
     *
     * Pivot Attribute
     * ● Untuk mendapatkan informasi dari Intermediate Table, kita bisa menggunakan attribute bernama
     *   pivot pada Model, yang secara otomatis akan mengambil semua isi kolom dari Intermediate Table
     * ● Secara default, cuma FK Model 1 dan Model 2 saja yang akan di query di Pivot Attribute.
     * ● Jika kita ingin tambahkan kolom lain, kita bisa tambahkan pada relasi BelongsToMany dengan
     *   menambah withPivot() pada model yang berelasi many to many
     *
     * // buat method yang impl withPivot()
     * // di model Customer dan Product
     */

    public function testPivotAttribute()
    {
        $this->testInsertManyToMany();

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::find("BUDHI");
        Log::info(json_encode($customer));

        // sql: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id`, `customers_likes_products`.`created_at` as `pivot_created_at` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
        $products = $customer->likeProducts;
        self::assertNotNull($products);
        Log::info(json_encode($products));

        foreach ($products as $product){
            $pivot = $product->pivot; // pivot{} // adalah object isi dari table pivot many to many dari customers dan products
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at); // attribute created_at akan binding dengan column created_at di table
        }

        /**
         * result:
         * [2024-06-26 05:14:10] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 05:14:10] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-26 05:14:10] testing.INFO: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id`, `customers_likes_products`.`created_at` as `pivot_created_at` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
         * [2024-06-26 05:14:10] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD","pivot":{"customer_id":"BUDHI","product_id":"1","created_at":"2024-06-26T05:14:10.000000Z"}}]
         */
    }

    /**
     * Filtering via Intermediate Table
     * ● Kita juga bisa melakukan filtering melalui Intermediate Table
     * ● Contoh, kita ingin mengambil data Product yang di Like Customer, tapi created_at nya yang satu
     *   minggu yang lalu misalnya
     * ● Kita bisa tambahkan kondisi pada relasi BelongsToMany dengan menambahkan method dengan
     *   prefix wherePivot()
     */

    public function testPivotAttributeCondition()
    {
        $this->testInsertManyToMany();

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::find("BUDHI");
        Log::info(json_encode($customer));

        // sql: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id`, `customers_likes_products`.`created_at` as `pivot_created_at` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ? and `customers_likes_products`.`created_at` >= ?
        $products = $customer->likeProductsLastWeek; // ambil data seminggu terakhir
        Log::info(json_encode($products));

        foreach ($products as $product){
            $pivot = $product->pivot; // pivot{} // adalah object isi dari table pivot many to many dari customers dan products
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at); // attribute created_at akan binding dengan column created_at di table
        }

        /**
         * result:
         * [2024-06-26 05:21:29] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 05:21:29] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-26 05:21:29] testing.INFO: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id`, `customers_likes_products`.`created_at` as `pivot_created_at` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ? and `customers_likes_products`.`created_at` >= ?
         * [2024-06-26 05:21:29] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD","pivot":{"customer_id":"BUDHI","product_id":"1","created_at":"2024-06-26T05:21:29.000000Z"}}]
         */
    }




    /**
     * Pivot Model
     * ● Jika Intermediate Table memiliki kolom selain kolom untuk Join, kadang ada baiknya dibuat dalam
     *   bentuk Pivot Model
     * ● Pivot Model adalah Model seperti biasanya, hanya saja harus turunan dari Pivot, bukan Model
     * ● Salah satu kelebihan menambahkan Pivot Model adalah, kita bisa query data secara langsung lewat
     *   Pivot Model atau menambahkan relasi pada Pivot Model
     *
     * Pivot Class
     * ● Pivot Class sebenarnya adalah turunan dari Model class, oleh karena itu hampir semua yang bisa
     *   dilakukan di Model, bisa juga dilakukan di Pivot
     * ● Namun pada Pivot Class, secara default $incrementing bernilai false, jadi jika kita membuat Pivot
     *   Model dengan auto increment, maka kita harus mengubah nilai $incrementing nya menjadi true
     * ● Selain itu, Pivot Model tidak mendukung SoftDeletes, jika kita ingin menggunakan SoftDeletes, kita
     *   perlu mengubah Pivot Model, menjadi Model biasa pada Eloquent
     */

    public function testPivotModel()
    {
        $this->testInsertManyToMany();

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::find("BUDHI");
        Log::info(json_encode($customer));

        // sql: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id`, `customers_likes_products`.`created_at` as `pivot_created_at` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
        $products = $customer->likeProducts;
        Log::info(json_encode($products));

        foreach ($products as $product){
            $pivot = $product->pivot; // object Model Like
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);

            // sql: select * from `customers` where `customers`.`id` = ? limit 1
            self::assertNotNull($pivot->customer);

            // sql: select * from `products` where `products`.`id` = ? limit 1
            self::assertNotNull($pivot->product);
        }

        /**
         * result:
         * [2024-06-26 06:23:56] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 06:23:56] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-26 06:23:56] testing.INFO: select `products`.*, `customers_likes_products`.`customer_id` as `pivot_customer_id`, `customers_likes_products`.`product_id` as `pivot_product_id`, `customers_likes_products`.`created_at` as `pivot_created_at` from `products` inner join `customers_likes_products` on `products`.`id` = `customers_likes_products`.`product_id` where `customers_likes_products`.`customer_id` = ?
         * [2024-06-26 06:23:56] testing.INFO: [{"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD","pivot":{"customer_id":"BUDHI","product_id":"1","created_at":"2024-06-26 06:23:56"}}]
         * [2024-06-26 06:23:57] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 06:23:57] testing.INFO: select * from `products` where `products`.`id` = ? limit 1
         */
    }

}
