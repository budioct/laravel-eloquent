<?php

namespace Tests\Feature;

use App\Models\Customer;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LazyAndEagerTest extends TestCase
{
    /**
     * Lazy Loading
     * ● Secara default, semua relasi akan di load (ambil) datanya secara Lazy
     * ● Lazy artinya, ketika kita panggil attribute nya, baru Laravel akan melakukan query untuk
     *   mendapatkan datanya
     * ● Salah satu keuntungan menggunakan Lazy adalah, ketika kita tidak butuh datanya, Laravel tidak
     *   akan melakukan query
     *
     * Eager Loading
     * ● Namun pada kasus tertentu, kadang kita ingin melakukan Eager Loading
     * ● Yaitu langsung mengambil data relasi secara langsung ketika kita mengambil data Model
     * ● Terdapat dua cara untuk melakukannya, pertama dengan menggunakan Query Builder, atau
     *   langsung di hardcode di Model nya
     * ● Menggunakan Query Builder bisa kita pilih, apakah mau di menggunakan Eager Loading atau tidak
     * ● Jika menggunakan Model, secara otomatis akan dilakukan Eager Loading
     * ● Untuk menggunakan Query Builder, kita bisa gunakan method with([relation])
     * ● Untuk menggunakan Model, kita bisa override attribute $with
     */

    public function testLazyLoading(){

        $this->seed([
            CustomerSeeder::class,
            WalletSeeder::class,
            ImageSeeder::class
        ]);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI"); // jika kita query seperti ini akan lazy loading.. karna relasi dari model Customer tidak kita sebutkan
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        /**
         * result:
         * [2024-06-27 01:52:09] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-27 01:52:09] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         */

    }

    public function testEagerLoading(){

        $this->seed([
            CustomerSeeder::class,
            WalletSeeder::class,
            ImageSeeder::class
        ]);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->find("BUDHI"); // jika kita query seperti ini akan lazy loading.. karna relasi dari model Customer tidak terbawa
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        // karna kita di query builder tidak memangil model relasi,
        // sekarang kita panggil model relasi dari Customer yaitu Wallet dan Image
        $wallet = $customer->wallet;
        self::assertNotNull($wallet);
        Log::info(json_encode($wallet));

        $image = $customer->image;
        self::assertNotNull($image);
        Log::info(json_encode($image));


        /**
         * result:
         * [2024-06-27 01:55:30] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-27 01:55:30] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com"}
         * [2024-06-27 01:55:30] testing.INFO: select * from `wallets` where `wallets`.`customer_id` = ? and `wallets`.`customer_id` is not null limit 1
         * [2024-06-27 01:55:30] testing.INFO: {"id":16,"customer_id":"BUDHI","amount":7000}
         * [2024-06-27 01:55:30] testing.INFO: select * from `images` where `images`.`imageable_type` = ? and `images`.`imageable_id` = ? and `images`.`imageable_id` is not null limit 1
         * [2024-06-27 01:55:30] testing.INFO: {"id":15,"url":"https:\/\/www.programmerzamannow.com\/image\/1.jpg","imageable_id":"BUDHI","imageable_type":"customer"}
         */

    }

    public function testEagerLoadingQueryBuilder(){

        $this->seed([
            CustomerSeeder::class,
            WalletSeeder::class,
            ImageSeeder::class
        ]);

        // sql: select * from `customers` where `customers`.`id` = ? limit 1
        $customer = Customer::query()->with(["wallet", "image"])->find("BUDHI"); // with(["relation_model", "relation_model"]) // mengambil data relasi dari model Customer yaitu Wallet, Image
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        // cara di bawah terlalu verbose.. kita gunakan query builder saja dengan method with(["relation_model", "relation_model"])

        // karna kita di query builder tidak memangil model relasi,
        // sekarang kita panggil model relasi dari Customer yaitu Wallet dan Image
        //$wallet = $customer->wallet;
        //self::assertNotNull($wallet);
        //Log::info(json_encode($wallet));

        //$image = $customer->image;
        //self::assertNotNull($image);
        //Log::info(json_encode($image));

        /**
         * result:
         * [2024-06-27 01:58:54] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-27 01:58:54] testing.INFO: select * from `wallets` where `wallets`.`customer_id` in (?)
         * [2024-06-27 01:58:54] testing.INFO: select * from `images` where `images`.`imageable_id` in (?) and `images`.`imageable_type` = ?
         * [2024-06-27 01:58:54] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com","wallet":{"id":18,"customer_id":"BUDHI","amount":7000},"image":{"id":19,"url":"https:\/\/www.programmerzamannow.com\/image\/1.jpg","imageable_id":"BUDHI","imageable_type":"customer"}}
         */

    }

    public function testEagerLoadingFromModel(){

        $this->seed([
            CustomerSeeder::class,
            WalletSeeder::class,
            ImageSeeder::class
        ]);

        // selain menggunakn query builder kita bisa set method with di Model Customer,,
        // jadi kita tidak perlu set method with saat query builder
        $customer = Customer::query()->find("BUDHI");
        self::assertNotNull($customer);
        Log::info(json_encode($customer));

        /**
         * result:
         * [2024-06-27 02:07:07] testing.INFO: select * from `customers` where `customers`.`id` = ? limit 1
         * [2024-06-27 02:07:07] testing.INFO: select * from `wallets` where `wallets`.`customer_id` in (?)
         * [2024-06-27 02:07:07] testing.INFO: select * from `images` where `images`.`imageable_id` in (?) and `images`.`imageable_type` = ?
         * [2024-06-27 02:07:07] testing.INFO: {"id":"BUDHI","name":"budhi","email":"budhi@test.com","wallet":{"id":19,"customer_id":"BUDHI","amount":7000},"image":{"id":21,"url":"https:\/\/www.programmerzamannow.com\/image\/1.jpg","imageable_id":"BUDHI","imageable_type":"customer"}}
         */

    }

}
