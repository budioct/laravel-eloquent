<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Scopes\IsActiveScope;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QueryScopeTest extends TestCase
{
    /**
     * Query Scope
     * ● Saat kita menambahkan trait SoftDeletes, secara otomatis Model akan memiliki Query Scope
     * ● Query Scope adalah mekanisme cara menambahkan kondisi query secara otomatis sehingga tidak
     *   perlu manual lagi setiap kita melakukan query ke database untuk Model tersebut
     * ● Jika kita mau, kita juga bisa menambahkan fitur Query Scope pada Model yang kita buat
     *
     * Jenis Query Scope
     * ● Terdapat dua jenis Query Scope, yaitu
     * ● Global Scope, dan
     * ● Local Scope
     * ● Detailnya akan kita bahas di materi masing-masing
     */

    /**
     * Query Global Scope
     * ● Query Global Scope merupakan kondisi query yang bisa kita tambahkan secara default ke Model
     * ● Dengan menambahkan Global Scope, secara otomatis ketika kita melakukan query apapun, query
     *   tambahkan di Global Scope akan ditambahkan ke Query Builder secara otomatis
     * ● Contoh pada SoftDeletes, trait tersebut secara otomatis menambahkan kondisi :
     *   where deleted_at is null
     * ● Walaupun kita tidak pernah melakukannya secara manual
     *
     * Fitur Active dan Non Active
     * ● Contoh kita akan menambahkan fitur Active dan Non Active pada Category dengan cara
     *   menambahkan kolom is_active : boolean
     * ● Selanjutnya kita akan menambahkan Global Scope agar setiap kita melakukan query, kita selalu
     *   mengambil data Category yang Active
     *
     * Membuat Global Scope
     * ● Untuk membuat Global Scope, kita perlu membuat Scope terlebih dahulu menggunakan perintah
     *   php artisan make:scope NamaScope
     * ● Selanjutnya kita bisa tambahkan isi kondisi pada Scope yang sudah kita buat
     * ● Setelah itu, kita bisa tambahkan Scope yang sudah kita buat ke Model dengan cara meng-override
     *   method booted() dan menggunakan method addGlobalScope(scope)
     *
     * // buat migration
     * // buat global scope
     * // set model (register global scope)
     *
     * Mematikan Global Scope
     *  ● Kadang kita butuh mematikan Global Scope
     *  ● Kita bisa menggunakan method withoutGlobalScope(array) pada Query Builder dengan parameter
     *    daftar Global Scope yang ingin kita hilangkan
     *
     *  note: query local scope akan aktif query tambahan ketika kita panggil method yang telah kita buat di model/entity
     *        query global scope akan selalu aktif keti tidak di panggil
     */

    public function testGlobalScope(){

        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = false;

        // sql: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
        $category->save();

        // sql: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
        $data = Category::query()->find("FOOD");
        self::assertNull($data); // kenapa hasil query null, karena kita hanya ambil data jika is_active = true.. sedangakan datanya is_active = false jadi datanya tidak dapat

        // mematikan Global Scope
        //$category = Category::withoutGlobalScopes([IsActiveScope::class])->find("FOOD"); // not support laravel ver 9.. laravel ver ^10
        //self::assertNotNull($category);

        /**
         * result:
         * [2024-06-23 03:50:27] testing.INFO: insert into `categories` (`id`, `name`, `description`, `is_active`) values (?, ?, ?, ?)
         * [2024-06-23 03:50:27] testing.INFO: select * from `categories` where `categories`.`id` = ? and `is_active` = ? limit 1
         */

    }


    /**
     * Query Local Scope
     * ● Selain Global Scope, terdapat Local Scope, perbedaan dari Local Scope adalah, secara default tidak
     *   akan aktif, kecuali kita mengaktifkannya ketika melakukan Query
     * ● Untuk membuat Local Scope, kita bisa membuat method di Model dengan awalan scope lalu diikuti
     *   dengan nama scope nya, misal scopeActive(), scopeNonActive(), dan lain-lain
     * ● Method untuk Local Scope memerlukan parameter Builder yang bisa kita gunakan untuk
     *   menggunakan menambah kondisi
     * ● https://laravel.com/api/10.x/Illuminate/Database/Eloquent/Builder.html
     *
     * Menggunakan Query Local Scope
     * ● Untuk menggunakan Local Scope, kita bisa memanggil method nya, diawali dengan lowercase, dan
     *   tanpa prefix scope, ketika menggunakan Builder, misal :
     * ● Local Scope scopeActive(), dipanggil active()
     * ● Local Scope scopeNonActive(), dipanggil nonActive()
     *
     * // buat migration
     * // set method di model/entity untuk query local scope
     *
     * note: query local scope akan aktif query tambahan ketika kita panggil method yang telah kita buat di model/entity
     *       query global scope akan selalu aktif keti tidak di panggil
     */

    public function testLocalScope(){

        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->is_active = true;

        // sql: insert into `vouchers` (`name`, `is_active`, `id`, `voucher_code`) values (?, ?, ?, ?)
        $voucher->save();

        // test ketika query tanpa menggunakan query local scope (startard laravel)
        // sql: select count(*) as aggregate from `vouchers` where `vouchers`.`deleted_at` is null
        $total = Voucher::query()->count();
        self::assertEquals(1, $total);

        // sql: select count(*) as aggregate from `vouchers` where `is_active` = ? and `vouchers`.`deleted_at` is null // `is_active` = ? .. true
        $total = Voucher::query()->active()->count();
        self::assertEquals(1, $total);

        // sql: select count(*) as aggregate from `vouchers` where `is_active` = ? and `vouchers`.`deleted_at` is null // `is_active` = ? .. false
        $total = Voucher::query()->nonActive()->count();
        self::assertEquals(0, $total);

        /**
         * result:
         * [2024-06-23 05:34:08] testing.INFO: insert into `vouchers` (`name`, `is_active`, `id`, `voucher_code`) values (?, ?, ?, ?)
         * [2024-06-23 05:34:08] testing.INFO: select count(*) as aggregate from `vouchers` where `vouchers`.`deleted_at` is null
         * [2024-06-23 05:34:08] testing.INFO: select count(*) as aggregate from `vouchers` where `is_active` = ? and `vouchers`.`deleted_at` is null
         * [2024-06-23 05:34:08] testing.INFO: select count(*) as aggregate from `vouchers` where `is_active` = ? and `vouchers`.`deleted_at` is null
         */

    }

}
