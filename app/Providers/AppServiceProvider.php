<?php

namespace App\Providers;

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
    }
}
