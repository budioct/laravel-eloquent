<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
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




    /**
     * Soft Delete
     * ● Secara default, saat kita melakukan operasi DELETE, data di table database akan di hapus secara
     *   permanen
     * ● Terdapat konsep yang bernama SOFT DELETE, yaitu konsep dimana ketika kita menghapus data,
     *   maka kita sebenarnya hanya menandai di database bahwa row tersebut dihapus, sehingga
     *   sebenarnya datanya masih tetap ada di tabel
     * ● Untuk menandainya, di Laravel biasanya menambahkan kolom tambahan deleted_at, dimana jika
     *   ada nilainya, berarti data dianggap dihapus
     * ● Artinya seluruh query ke database pun, harus diberi kondisi dimana deleted_at nilainya null, agar
     *   hasil query adalah data yang belum dihapus
     *
     * // buat migration
     * // set model
     * // set seender
     *
     * Trait SoftDeletes
     * ● Untuk implementasi Soft Delete di Model, kita bisa menggunakan trait SoftDeletes
     * ● Dan saat membuat tabel, kita harus menambahkan kolom deleted_at dengan tipe data timestamp,
     *   atau bisa menggunakan method softDeletes() di Migrations, yang secara otomatis akan dibuatkan
     *   kolom yang dibutuhkan
     *
     * Delete
     * ● Untuk melakukan soft delete, kita bisa gunakan method delete() seperti biasa
     * ● Untuk memaksa menghapus dari tabel secara permanent, kita bisa gunakan method forceDelete()
     */

    public function testDeleteVoucher(){

        // sql: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
        $this->seed(VoucherSeeder::class);

        // kenapa sql ada kondisi ~and `vouchers`.`deleted_at` is null limit 1~ karna kita sudah use trait use SoftDeletes yang akan handle query dan soft_delete update column "deleted_at"
        // sql: select * from `vouchers` where `name` = ? limit 1
        $voucher = Voucher::query()->where("name", "=", "Sample Voucher")->first();

        // sql: elete from `vouchers` where `id` = ?
        $voucher->delete();

        // sql: select * from `vouchers` where `name` = ? limit 1
        $voucher = Voucher::query()->where("name", "=", "Sample Voucher")->first();

        self::assertNull($voucher);

        /**
         * result:
         * [2024-06-23 03:15:00] testing.INFO: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
         * [2024-06-23 03:15:00] testing.INFO: select * from `vouchers` where `name` = ? limit 1
         * [2024-06-23 03:15:00] testing.INFO: delete from `vouchers` where `id` = ?
         * [2024-06-23 03:15:00] testing.INFO: select * from `vouchers` where `name` = ? limit 1
         */

    }

    /**
     * Query Soft Delete
     * ● Secara default, saat kita melakukan query dari Model yang memiliki fitur soft delete, maka akan
     *   selalu otomatis ditambah kondisi deleted_at is null
     * ● Namun jika kita benar-benar ingin mengambil seluruh data termasuk yang sudah di soft delete, kita
     *   bisa gunakan withTrashed() saat membuat query
     */

    public function testSoftDeleteVoucher(){

        // sql: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
        $this->seed(VoucherSeeder::class);

        // kenapa sql ada kondisi ~and `vouchers`.`deleted_at` is null limit 1~ karna kita sudah use trait use SoftDeletes yang akan handle query dan soft_delete update column "deleted_at"
        // sql: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
        $voucher = Voucher::query()->where("name", "=", "Sample Voucher")->first();

        // sql: update `vouchers` set `deleted_at` = ? where `id` = ?
        $voucher->delete();

        // sql: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
        $voucher = Voucher::query()->where("name", "=", "Sample Voucher")->first();
        self::assertNull($voucher);

        /**
         * result:
         * [2024-06-23 03:01:21] testing.INFO: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
         * [2024-06-23 03:01:21] testing.INFO: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
         * [2024-06-23 03:01:21] testing.INFO: update `vouchers` set `deleted_at` = ? where `id` = ?
         * [2024-06-23 03:01:21] testing.INFO: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
         */

    }

    public function testQuerySoftDeleteVoucher(){

        // sql: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
        $this->seed(VoucherSeeder::class);

        // kenapa sql ada kondisi ~and `vouchers`.`deleted_at` is null limit 1~ karna kita sudah use trait use SoftDeletes yang akan handle query dan soft_delete update column "deleted_at"
        // sql: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
        $voucher = Voucher::query()->where("name", "=", "Sample Voucher")->first();

        // sql: update `vouchers` set `deleted_at` = ? where `id` = ?
        $voucher->delete();

        // sql: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
        $voucher = Voucher::query()->where("name", "=", "Sample Voucher")->first();
        self::assertNull($voucher);

        // Query Yang Sudah Di Delete
        // sql: select * from `vouchers` where `name` = ? limit 1
        $voucher = Voucher::withTrashed()->where("name", "=", "Sample Voucher")->first();
        self::assertNotNull($voucher);
        Log::info(json_encode($voucher));

        /**
         * result:
         * [2024-06-23 03:01:21] testing.INFO: insert into `vouchers` (`name`, `voucher_code`, `id`) values (?, ?, ?)
         * [2024-06-23 03:01:21] testing.INFO: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
         * [2024-06-23 03:01:21] testing.INFO: update `vouchers` set `deleted_at` = ? where `id` = ?
         * [2024-06-23 03:01:21] testing.INFO: select * from `vouchers` where `name` = ? and `vouchers`.`deleted_at` is null limit 1
         * [2024-06-23 03:01:21] testing.INFO: select * from `vouchers` where `name` = ? limit 1
         * [2024-06-23 03:01:21] testing.INFO: {"id":"9c5a301b-9e87-4f60-adb7-868f3bb01646","name":"Sample Voucher","voucher_code":"22223333","create_at":"2024-06-23 10:01:21","deleted_at":"2024-06-23T03:01:21.000000Z"}
         */

    }
}
