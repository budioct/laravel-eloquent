<?php

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AccessorDanMutatorTest extends TestCase
{
    /**
     * Accessor dan Mutator
     * ● Saat kita mengambil dan mengubah attribute di object Model, secara otomatis akan mengambil
     *   data di kolom table database
     * ● Laravel memiliki fitur untuk mengubah data ketika di akses (Accessor) dan mengubah data ketika di set (Mutator)
     * ● Caranya kita bisa membuat dengan menggunakan function yang mengembalikan object Attribute
     * ● https://laravel.com/api/10.x/Illuminate/Database/Eloquent/Casts/Attribute.html
     *
     * note: jadi fitur ini akan
     * - mengambil data di sebut (Accessor)
     * - mengubah data di sebut (Mutator)
     * fitur ini diguanakan untuk sebelum pengakses dan mengubah data aslinya!!!
     *
     *  Accessor dan Mutator Sama dengan Kolom
     *  ● Saat membuat Accessor dan Mutator, kita juga bisa buat sama dengan nama kolom nya
     *  ● Untuk mendapatkan value yang asli dari kolom, kita bisa tambahkan satu parameter di function Accessor
     *  ● Dan pada kasus tertentu, jika ingin mengambil semua kolom, kita bisa tambahkan dua parameter
     *    pada function Accessor nya
     */

    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Budhi"; // property 'first_name' sudah di set oleh Accessor menjadi set to uppercase
        $person->last_name = "Octaviansyah";

        // sql: insert into `person` (`first_name`, `last_name`) values (?, ?)
        $person->save();

        // Accessor (mengakses data)
        self::assertEquals("BUDHI Octaviansyah", $person->full_name); // full_name atau fullName adalah method yang di buat untuk  Accessor dan Mutator
        Log::info(json_encode($person));

        // Mutator (mengubah data)
        $person->full_name = "Joko Setiawan"; // full_name atau fullName adalah method yang di buat untuk  Accessor dan Mutator
        Log::info(json_encode($person));

        // sql: update `person` set `first_name` = ?, `last_name` = ? where `id` = ?
        $person->save();

        self::assertEquals("JOKO", $person->first_name);
        self::assertEquals("Setiawan", $person->last_name);
        Log::info(json_encode($person));

        /**
         * result:
         * [2024-06-27 07:19:57] testing.INFO: insert into `person` (`first_name`, `last_name`) values (?, ?)
         * [2024-06-27 07:19:57] testing.INFO: {"first_name":"Budhi","last_name":"Octaviansyah","id":3}
         * [2024-06-27 07:19:57] testing.INFO: {"first_name":"Joko","last_name":"Setiawan","id":3}
         * [2024-06-27 07:19:57] testing.INFO: update `person` set `first_name` = ?, `last_name` = ? where `id` = ?
         * [2024-06-27 07:19:57] testing.INFO: {"first_name":"Joko","last_name":"Setiawan","id":3}
         */
    }

}
