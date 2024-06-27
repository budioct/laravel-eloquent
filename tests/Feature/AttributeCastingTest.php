<?php

namespace Tests\Feature;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AttributeCastingTest extends TestCase
{
    /**
     * Attribute Casting
     * ● Attribute Casting adalah fitur di Eloquent untuk melakukan konversi tipe data secara otomatis dari
     *   tipe data di database, dengan tipe data yang ada di PHP
     * ● Cara kerjanya sebenarnya sama seperti Accessor dan Mutator, hanya saja kita tidak perlu
     *   melakukannya secara manual, tinggal simpan pemetaan Attribute Casting nya di attribute $casts di Model
     * ● $casts adalah array, dimana key nya berisi attribute / kolom, dan value nya berisi tipe data tujuan
     *   melakukan casting
     * ● Tipe data casting yang didukung oleh Eloquent bisa dilihat disini :
     * ● https://laravel.com/docs/10.x/eloquent-mutators#attribute-casting
     */

    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = "Budhi";
        $person->last_name = "Octaviansyah";

        // sql: insert into `person` (`first_name`, `last_name`, `updated_at`, `created_at`) values (?, ?, ?, ?)
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
        Log::info(json_encode($person));

        /**
         * result:
         * [2024-06-27 08:46:06] testing.INFO: insert into `person` (`first_name`, `last_name`, `updated_at`, `created_at`) values (?, ?, ?, ?)
         * [2024-06-27 08:46:06] testing.INFO: {"first_name":"BUDHI","last_name":"Octaviansyah","updated_at":"2024-06-27T08:46:06.000000Z","created_at":"2024-06-27T08:46:06.000000Z","id":10}
         */
    }


}
