<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Voucher;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PolymorphicRelationshipsTest extends TestCase
{
    /**
     * Polymorphic Relationships
     * ● Polymorphic Relationship adalah relasi antar tabel namun relasinya bisa berbeda Model
     * ● Relasi ini sebenarnya tidak standard dalam relational database, karena dalam relational database,
     *   satu kolom FK hanya bisa mengacu ke satu tabel, sedangkan pada Polymorphic Relationship, satu
     *   kolom FK bisa mengacu ke beberapa tabel yang berbeda, tergantung tipe relasi nya
     * ● Karena itu, sebenarnya relasi Polymorphic sendiri tidak saya anjurkan untuk terlalu banyak
     *   digunakan, kecuali dalam keadaan tertentu
     * ● Dan karena relational database tidak mendukung satu kolom FK untuk lebih dari satu tabel, oleh
     *   karena itu implementasi Polymorphic Relationship ini tidak akan menggunakan foreign key di database
     * ● https://stackoverflow.com/questions/441001/possible-to-do-a-mysql-foreign-key-to-one-of-two-possible-tables
     *
     * Jenis Polymorphic Relationships
     * ● Laravel mendukung banyak Polymorphic Relationships, yaitu
     * ● One to One Polymorphic
     * ● One to Many Polymorphic
     * ● One of Many Polymorphic
     * ● Many to Many Polymorphic
     *
     */

    /**
     * One to One Polymorphic
     * ● One to One Polymorphic sebenarnya mirip seperti relasi One to One, hanya saja, relasinya bisa ke
     *   lebih dari satu Model
     * ● Contoh, kita cukup Customer dan punya Product, misal saja Customer dan Product punya satu Image
     * ● Contoh, kita cukup Customer dan punya Product, misal saja Customer dan Product punya satu Image
     * ● Artinya kita bisa membuat Model Image yang berelasi One to One dengan Customer atau Product
     *
     * Kolom di Polymorphic
     * ● Saat kita membuat relasi Polymorphic, kita harus membuat kolom nama relasinya, misal imageable
     *   di tabel images
     * ● Kolom harus dibuat dalam dua kolom, pertama _id dan kedua _type, misal imageable_id dan
     *   imageable_type
     * ● Dimana di imageable_id, isinya adalah FK pada primary key di tabel relasi
     * ● Sedangkan imageable_type isinya adalah tipe Model, biasanya diisi dengan nama class Model
     *
     * note:
     * jadi nanti kita akan buat 2 kolom untuk referensi polumorphic yang berlasi ke table Image yaitu tabel Customers dan Products
     * nama column nya seperti imageable,
     * pertama imageable_id sebagaua FK daru primary_key tabek relasi
     * kedua imageable_type sebagai tipe_Model diisi dengan nama class Model
     */

    public function testOneToOnePolymorphicRelationCustomers(){

        $this->seed([
            CustomerSeeder::class,
            ImageSeeder::class,
        ]);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI");
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        // sql: select * from `images` where `images`.`imageable_type` = ? and `images`.`imageable_id` = ? and `images`.`imageable_id` is not null limit 1
        $image = $customer->image;
        self::assertNotNull($image);
        self::assertEquals("https://www.programmerzamannow.com/image/1.jpg", $image->url);
        Log::info(json_encode($image));

        /**
         * result:
         * [2024-06-26 09:14:53] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-26 09:14:53] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-26 09:14:53] testing.INFO: select * from `images` where `images`.`imageable_type` = ? and `images`.`imageable_id` = ? and `images`.`imageable_id` is not null limit 1
         * [2024-06-26 09:14:53] testing.INFO: {"id":3,"url":"https:\/\/www.programmerzamannow.com\/image\/1.jpg","imageable_id":"BUDHI","imageable_type":"App\\Models\\Customer"}
         */

    }

    public function testOneToOnePolymorphicRelationProducts()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            ImageSeeder::class
        ]);

        // sql: select * from `products` where `products`.`id` = ? limit 1
        $product = Product::find("1");
        self::assertNotNull($product);
        Log::info($product);

        // sql: select * from `images` where `images`.`imageable_type` = ? and `images`.`imageable_id` = ? and `images`.`imageable_id` is not null limit 1
        $image = $product->image;
        self::assertNotNull($image);
        self::assertEquals("https://www.programmerzamannow.com/image/2.jpg", $image->url);
        Log::info($image);

        /**
         * result:
         * [2024-06-26 09:18:23] testing.INFO: select * from `products` where `products`.`id` = ? limit 1
         * [2024-06-26 09:18:23] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}
         * [2024-06-26 09:18:23] testing.INFO: select * from `images` where `images`.`imageable_type` = ? and `images`.`imageable_id` = ? and `images`.`imageable_id` is not null limit 1
         * [2024-06-26 09:18:23] testing.INFO: {"id":6,"url":"https:\/\/www.programmerzamannow.com\/image\/2.jpg","imageable_id":"1","imageable_type":"App\\Models\\Product"}
         */
    }




    /**
     * One to Many Polymorphic
     * ● Selain One to One, Polymorphic juga mendukung relasi One to Many
     * ● Sebenarnya cara pembuatan One to One dan One to Many hampir mirip, bedanya adalah pada
     *   tabel, kita tidak menambahkan Unique Constraint, karena bisa lebih dari satu
     * ● Contoh kasus, misal kita sebelumnya sudah membuat model Comment
     * ● Misal, kita akan membuat relasi One to Many Polymorphic pada Comment dengan Product dan Voucher
     * ● Artinya kita bisa menambah Comment ke Product dan juga Voucher, dan lebih dari satu Comment
     */

    public function testOneToManyPolymorphicRelationProducts()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VoucherSeeder::class,
            CommentSeeder::class
        ]);

        // sql: select * from `products` where `products`.`id` = ? limit 1
        $product = Product::find("1");
        self::assertNotNull($product);
        Log::info(json_encode($product));

        // sql: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null
        $comments = $product->comments;
        foreach ($comments as $comment){
            self::assertEquals(Product::class, $comment->commentable_type);
            self::assertEquals($product->id, $comment->commentable_id);
            Log::info(json_encode($comment));
        }

        /**
         * result:
         * [2024-06-26 09:46:40] testing.INFO: select * from `products` where `products`.`id` = ? limit 1
         * [2024-06-26 09:46:40] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}
         * [2024-06-26 09:46:40] testing.INFO: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null
         * [2024-06-26 09:46:40] testing.INFO: {"id":9,"email":"budhi@test.com","title":"Title","comment":"Sample Comment","commentable_id":"1","commentable_type":"App\\Models\\Product","created_at":"2024-06-26T09:46:40.000000Z","updated_at":"2024-06-26T09:46:40.000000Z"}
         */
    }

    public function testOneToManyPolymorphicRelationVouchers()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VoucherSeeder::class,
            CommentSeeder::class
        ]);

        // sql: select * from `vouchers` where `vouchers`.`deleted_at` is null limit 1
        $voucher = Voucher::query()->firstOrFail();
        self::assertNotNull($voucher);
        Log::info(json_encode($voucher));

        // sql: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null
        $comments = $voucher->comments;
        foreach ($comments as $comment){
            self::assertEquals(Voucher::class, $comment->commentable_type);
            self::assertEquals($voucher->id, $comment->commentable_id);
            Log::info(json_encode($comment));
        }

        /**
         * result:
         * [2024-06-26 09:51:48] testing.INFO: select * from `vouchers` where `vouchers`.`deleted_at` is null limit 1
         * [2024-06-26 09:51:48] testing.INFO: {"id":"9c60cbd9-8bbd-4390-93ac-2c0f5ae2a4c1","name":"Sample Voucher","voucher_code":"22223333","create_at":"2024-06-26 16:51:48","deleted_at":null,"is_active":1}
         * [2024-06-26 09:51:48] testing.INFO: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null
         * [2024-06-26 09:51:48] testing.INFO: {"id":12,"email":"budhi@test.com","title":"Title","comment":"Sample Comment","commentable_id":"9c60cbd9-8bbd-4390-93ac-2c0f5ae2a4c1","commentable_type":"App\\Models\\Voucher","created_at":"2024-06-26T09:51:48.000000Z","updated_at":"2024-06-26T09:51:48.000000Z"}
         */
    }




    /**
     * One of Many Polymorphic
     * ● Relasi One to Many Polymorphic juga mendukung penambahan kondisi, seperti yang pernah kita
     *   pelajari di materi Has One of Many
     * // contoh kasus pada model Product kita ingin mengabil data lama dan baru berdasarkan column created_at
     */

    public function testOneOfManyPolymorphic()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VoucherSeeder::class,
            CommentSeeder::class
        ]);

        // sql: select * from `products` where `products`.`id` = ? limit 1
        $product = Product::find("1");
        self::assertNotNull($product);
        Log::info(json_encode($product));

        // sql: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null order by `created_at` desc limit 1
        $comment = $product->latestComment; // latestComment // ambil data paling baru
        self::assertNotNull($comment);
        Log::info(json_encode($comment));

        // sql: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null order by `created_at` asc limit 1
        $comment = $product->oldestComment; // oldestComment // ambil data paling lama
        self::assertNotNull($comment);
        Log::info(json_encode($comment));

        /**
         * result:
         * [2024-06-26 10:17:25] testing.INFO: select * from `products` where `products`.`id` = ? limit 1
         * [2024-06-26 10:17:25] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}
         * [2024-06-26 10:17:25] testing.INFO: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null order by `created_at` desc limit 1
         * [2024-06-26 10:17:25] testing.INFO: {"id":13,"email":"budhi@test.com","title":"Title","comment":"Sample Comment","commentable_id":"1","commentable_type":"App\\Models\\Product","created_at":"2024-06-26T10:17:25.000000Z","updated_at":"2024-06-26T10:17:25.000000Z"}
         * [2024-06-26 10:17:25] testing.INFO: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null order by `created_at` asc limit 1
         * [2024-06-26 10:17:25] testing.INFO: {"id":13,"email":"budhi@test.com","title":"Title","comment":"Sample Comment","commentable_id":"1","commentable_type":"App\\Models\\Product","created_at":"2024-06-26T10:17:25.000000Z","updated_at":"2024-06-26T10:17:25.000000Z"}
         */
    }




    /**
     * Many to Many Polymorphic
     * ● Terakhir, kita juga bisa melakukan relasi Many to Many Polymorphic
     * ● Contoh, misal kita akan membuat Model Tag, dimana satu Tag bisa digunakan di banyak Voucher
     *   dan Product. Begitu juga kebalikannya, Satu Voucher atau Product bisa punya banyak Tag
     */

    public function testManyToManyPolymorphic()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VoucherSeeder::class,
            TagSeeder::class
        ]);

        // sql: select * from `products` where `products`.`id` = ? limit 1
        $product = Product::find("1");
        Log::info(json_encode($product));

        // sql: select `tags`.*, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `tags` inner join `taggables` on `tags`.`id` = `taggables`.`tag_id` where `taggables`.`taggable_id` = ? and `taggables`.`taggable_type` = ?
        $tags = $product->tags;
        self::assertNotNull($tags);
        self::assertCount(1, $tags);
        Log::info(json_encode($tags));

        foreach ($tags as $tag){
            self::assertNotNull($tag->id);
            self::assertNotNull($tag->name);

            // sql: select `vouchers`.*, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `vouchers` inner join `taggables` on `vouchers`.`id` = `taggables`.`taggable_id` where `taggables`.`tag_id` = ? and `taggables`.`taggable_type` = ? and `vouchers`.`deleted_at` is null
            $vouchers = $tag->vouchers;
            self::assertNotNull($vouchers);
            self::assertCount(1, $vouchers);
            Log::info(json_encode($vouchers));
        }

        /**
         * result:
         * [2024-06-26 10:46:40] testing.INFO: select * from `products` where `products`.`id` = ? limit 1
         * [2024-06-26 10:46:40] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}
         * [2024-06-26 10:46:40] testing.INFO: select `tags`.*, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `tags` inner join `taggables` on `tags`.`id` = `taggables`.`tag_id` where `taggables`.`taggable_id` = ? and `taggables`.`taggable_type` = ?
         * [2024-06-26 10:46:40] testing.INFO: [{"id":"aom","name":"Anak Om Mamat","pivot":{"taggable_id":"1","tag_id":"aom","taggable_type":"App\\Models\\Product"}}]
         * [2024-06-26 10:46:40] testing.INFO: select `vouchers`.*, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `vouchers` inner join `taggables` on `vouchers`.`id` = `taggables`.`taggable_id` where `taggables`.`tag_id` = ? and `taggables`.`taggable_type` = ? and `vouchers`.`deleted_at` is null
         * [2024-06-26 10:46:40] testing.INFO: [{"id":"9c60df78-e38e-4887-9297-7f14b483bfbf","name":"Sample Voucher","voucher_code":"22223333","create_at":"2024-06-26 17:46:40","deleted_at":null,"is_active":1,"pivot":{"tag_id":"aom","taggable_id":"9c60df78-e38e-4887-9297-7f14b483bfbf","taggable_type":"App\\Models\\Voucher"}}]
         */
    }




    /**
     * Polymorphic Types
     * ● Secara default, type di relasi Polymorphic akan menggunakan nama Class Model yang kita gunakan
     * ● Namun, hal ini bisa berbahaya misal kita mengubah nama Model atau mengubah namespace
     *   Model, karena secara otomatis type di Polymorphic tidak akan berjalan
     * ● Kadang, ada baiknya kita menambahkan type untuk Polymorphic
     * ● Kita bisa tambahkan pada Service Provider dengan manggil Relation::enforceMorphMap()
     *
     * // register model yang implement polymorphic di ../app/Providers/AppServiceProvider.php, di method boot()
     * Relation::enforceMorphMap([
     * 'product' => Product::class,
     * 'voucher' => Voucher::class,
     * 'customer' => Customer::class
     * ]);
     * // jadi yang di simpan pada column_able_type bukan lagi Model::class
     * // tapi key alias yang sudah di registrasikan oleh class modelnya seperti 'product', 'voucher', 'customer'
     */

    public function testOneToOneWithPolymorphicTypes(){

        $this->seed([
            CustomerSeeder::class,
            ImageSeeder::class,
        ]);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI");
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        // sql: select * from `images` where `images`.`imageable_type` = ? and `images`.`imageable_id` = ? and `images`.`imageable_id` is not null limit 1
        $image = $customer->image;
        self::assertNotNull($image);
        self::assertEquals("https://www.programmerzamannow.com/image/1.jpg", $image->url);
        Log::info(json_encode($image));

        /**
         * result:
         * [2024-06-27 01:20:38] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-27 01:20:38] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-27 01:20:38] testing.INFO: select * from `images` where `images`.`imageable_type` = ? and `images`.`imageable_id` = ? and `images`.`imageable_id` is not null limit 1
         * [2024-06-27 01:20:38] testing.INFO: {"id":11,"url":"https:\/\/www.programmerzamannow.com\/image\/1.jpg","imageable_id":"BUDHI","imageable_type":"customer"}
         */

    }

    public function testOneToManyWithPolymorphicTypes()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VoucherSeeder::class,
            CommentSeeder::class
        ]);

        // sql: select * from `vouchers` where `vouchers`.`deleted_at` is null limit 1
        $voucher = Voucher::query()->firstOrFail();
        self::assertNotNull($voucher);
        Log::info(json_encode($voucher));

        // sql: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null
        $comments = $voucher->comments;
        foreach ($comments as $comment){
            self::assertEquals("voucher", $comment->commentable_type);
            self::assertEquals($voucher->id, $comment->commentable_id);
            Log::info(json_encode($comment));
        }

        /**
         * result:
         * [2024-06-27 01:22:57] testing.INFO: select * from `vouchers` where `vouchers`.`deleted_at` is null limit 1
         * [2024-06-27 01:22:57] testing.INFO: {"id":"9c6218db-1812-4927-a15f-ac1990a2d867","name":"Sample Voucher","voucher_code":"22223333","create_at":"2024-06-27 08:22:57","deleted_at":null,"is_active":1}
         * [2024-06-27 01:22:57] testing.INFO: select * from `comments` where `comments`.`commentable_type` = ? and `comments`.`commentable_id` = ? and `comments`.`commentable_id` is not null
         * [2024-06-27 01:22:57] testing.INFO: {"id":18,"email":"budhi@test.com","title":"Title","comment":"Sample Comment","commentable_id":"9c6218db-1812-4927-a15f-ac1990a2d867","commentable_type":"voucher","created_at":"2024-06-27T01:22:57.000000Z","updated_at":"2024-06-27T01:22:57.000000Z"}
         */
    }

    public function testManyToManyWithPolymorphicTypes()
    {
        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            VoucherSeeder::class,
            TagSeeder::class
        ]);

        // sql: select * from `products` where `products`.`id` = ? limit 1
        $product = Product::find("1");
        Log::info(json_encode($product));

        // sql: select `tags`.*, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `tags` inner join `taggables` on `tags`.`id` = `taggables`.`tag_id` where `taggables`.`taggable_id` = ? and `taggables`.`taggable_type` = ?
        $tags = $product->tags;
        self::assertNotNull($tags);
        self::assertCount(1, $tags);
        Log::info(json_encode($tags));

        foreach ($tags as $tag){
            self::assertNotNull($tag->id);
            self::assertNotNull($tag->name);

            // sql: select `vouchers`.*, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `vouchers` inner join `taggables` on `vouchers`.`id` = `taggables`.`taggable_id` where `taggables`.`tag_id` = ? and `taggables`.`taggable_type` = ? and `vouchers`.`deleted_at` is null
            $vouchers = $tag->vouchers;
            self::assertNotNull($vouchers);
            self::assertCount(1, $vouchers);
            Log::info(json_encode($vouchers));
        }

        /**
         * result:
         * [2024-06-27 01:15:27] testing.INFO: select * from `products` where `products`.`id` = ? limit 1
         * [2024-06-27 01:15:27] testing.INFO: {"id":"1","name":"Product 1","description":"Description 1","price":0,"stock":0,"category_id":"FOOD"}
         * [2024-06-27 01:15:27] testing.INFO: select `tags`.*, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `tags` inner join `taggables` on `tags`.`id` = `taggables`.`tag_id` where `taggables`.`taggable_id` = ? and `taggables`.`taggable_type` = ?
         * [2024-06-27 01:15:27] testing.INFO: [{"id":"aom","name":"Anak Om Mamat","pivot":{"taggable_id":"1","tag_id":"aom","taggable_type":"product"}}]
         * [2024-06-27 01:15:27] testing.INFO: select `vouchers`.*, `taggables`.`tag_id` as `pivot_tag_id`, `taggables`.`taggable_id` as `pivot_taggable_id`, `taggables`.`taggable_type` as `pivot_taggable_type` from `vouchers` inner join `taggables` on `vouchers`.`id` = `taggables`.`taggable_id` where `taggables`.`tag_id` = ? and `taggables`.`taggable_type` = ? and `vouchers`.`deleted_at` is null
         * [2024-06-27 01:15:27] testing.INFO: [{"id":"9c62162c-b960-4f38-bee3-be2ba4877409","name":"Sample Voucher","voucher_code":"22223333","create_at":"2024-06-27 08:15:27","deleted_at":null,"is_active":1,"pivot":{"tag_id":"aom","taggable_id":"9c62162c-b960-4f38-bee3-be2ba4877409","taggable_type":"voucher"}}]
         */
    }


}
