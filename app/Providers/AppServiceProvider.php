<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Debug Query
         * ● Pada kasus tertentu, kadang kita ingin melakukan debugging SQL query yang dibuat oleh Laravel
         * ● Kita bisa menggunakan DB::listen()
         * ● DB::listen() akan dipanggil setiap kali ada operasi yang dilakukan oleh Laravel Database
         * ● Kita bisa me-log query misalnya
         * ● Kita bisa daftarkan DB::listen pada Service Provider
         */

        // DB::listen(callback) // kita akan dengar semua aktifitas DB Facade laravel
        // QueryExecuted object yang menerima interaksi query di laravel
        // Log::info() // semua aktifitas kita simpan dalam log.. nanti hasil log ada di directory ../storage/logs/laravel.log
        DB::listen(function (QueryExecuted $query) {
            Log::info($query->sql); // sql // properti yang mau kita simpan di log
        });

        /**
         * Polymorphic Types
         * ● Secara default, type di relasi Polymorphic akan menggunakan nama Class Model yang kita gunakan
         * ● Namun, hal ini bisa berbahaya misal kita mengubah nama Model atau mengubah namespace
         *   Model, karena secara otomatis type di Polymorphic tidak akan berjalan
         * ● Kadang, ada baiknya kita menambahkan type untuk Polymorphic
         * ● Kita bisa tambahkan pada Service Provider dengan manggil Relation::enforceMorphMap()
         */
        // Relation::enforceMorphMap(array $map) // register model yang implement polymorphic
        Relation::enforceMorphMap([
            'product' => Product::class,
            'voucher' => Voucher::class,
            'customer' => Customer::class,
        ]);

    }
}
