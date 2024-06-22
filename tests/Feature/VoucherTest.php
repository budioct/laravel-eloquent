<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    /**
     * UUID
     * ● Laravel Eloquent memiliki fitur untuk melakukan generate primary key untuk tipe string dengan
     *   otomatis menggunakan format UUID
     * ● UUID adalah random string yang dijamin unique, sehingga cocok untuk membuat random string
     *   sebagai primary key
     * ● Untuk menggunakan UUID, kita perlu menggunakan trait HasUuids pada Model nya
     * ● Secara default, Laravel Eloquent akan membuat UUID yang berurut, jadi kita tidak perlu takut
     *   dengan urutan datanya walaupun nilainya random
     * ● https://en.wikipedia.org/wiki/Universally_unique_identifier
     *
     * // buat model, migration, seeder
     * php artisan make:model Voucher -m -s
     * php artisan make:seeder VoucherSeeder
     *
     * // set migration
     * // set model
     */

    public function testCreateVoucher(){

        $voucher = new Voucher();
        $voucher->name = "voucher PB";
        $voucher->voucher_code = "332324345837594838749234";
        // sql: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
        $result = $voucher->save();

        self::assertTrue($result);
        Log::info(json_encode($voucher));

        /**
         * result:
         * [2024-06-22 05:27:19] testing.INFO: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
         * [2024-06-22 05:27:19] testing.INFO: {"name":"voucher PB","voucher_code":"332324345837594838749234","id":"9c586153-a905-4a6d-800c-fa32bc1d5fc1"}
         */

    }

    /**
     * UUID Selain Primary Key
     * ● Kadang ada kasus dimana kita ingin menggunakan UUID pada kolom selain primary key
     * ● Kita juga bisa melakukan hal itu, dengan cara meng-override method uniqueIds() pada trait HasUuids
     * ● Secara default, dia pengembalikan fields $primaryKey, kita bisa ubah jika kita mau
     *
     * ## setup model lagi
     * // secara default UUID hanya mengebalikan primaryKey saja.. tetapi kita bisa costom untuk salah satu column yang mau kita set UUID juga
     * // overrid method uniqueIds() dari trait HasUuids
     * public function uniqueIds()
     * {
     * return [$this->primaryKey, "voucher_code"]; // tambahkan name_column_table pada array yang mau di set UUID juga
     * }
     */

    public function testCreateVoucherUUID(){

        // note: kita tidak perlu add property voucher_code karna dia akan auto generate UUID seperti id primary_key

        $voucher = new Voucher();
        $voucher->name = "voucher PB";
        // $voucher->voucher_code = "332324345837594838749234"; // tidak perlu di set data

        // sql: insert into `vouchers` (`name`, `id`, `voucher_code`) values (?, ?, ?)
        $result = $voucher->save(); // save() // simpan data ke table db

        self::assertTrue($result);
        Log::info(json_encode($voucher));

        /**
         * result:
         * [2024-06-22 05:35:06] testing.INFO: insert into `vouchers` (`name`, `id`, `voucher_code`) values (?, ?, ?)
         * [2024-06-22 05:35:06] testing.INFO: {"name":"voucher PB","id":"9c58641d-2d63-460d-9c98-39d64b28df93","voucher_code":"9c58641d-3233-46b9-9771-03a83868fce5"}
         */

    }
}
