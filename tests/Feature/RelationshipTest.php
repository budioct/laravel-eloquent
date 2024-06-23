<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CustomerSeeder;
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
        $wallet = $customer->wallet;// akses model wallet dari model customer

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
}
