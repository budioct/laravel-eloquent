<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    /**
     * Insert
     * ● Untuk melakukan insert data, kita bisa menggunakan method save() setelah membuat object dari
     *   class Model yang kita buat dari model
     * ● Method save() akan mengembalikan status bool, jika sukses atau gagal
     */

    public function testInsertEloquent(){

        $category = new Category(); // instance model yang sudah binding database.table
        $category->id = "GADGET"; // binding nama property dengan nama column table
        $category->name = "Gadget";

        $result = $category->save(); // save():bool // simpan dari model ke database melalui ORM.

        self::assertTrue($result);

        /**
         * result:
         * [2024-06-16 06:38:22] testing.INFO: insert into `categories` (`id`, `name`) values (?, ?)
         */

    }

    /**
     * Query Builder
     * ● Setelah membuat Model, untuk melakukan operasi CRUD terhadap Model yang sudah kita buat,
     *   kita bisa menggunakan Query Builder
     * ● Caranya kita tidak perlu menggunakan Facade DB, kita cukup gunakan static method query() di
     *   Model yang sudah kita buat
     *
     * Magic Method
     * ● Beberapa tutorial ada yang tidak menggunakan method query() ketika melakukan operasi ke
     *   Model, kenapa?
     * ● Hal ini karena terdapat fitur Metaprogramming di Model, yang sebenarnya meneruskan method
     *   yang dipanggil ke Query Builder
     * ● Kita bisa lihat di source code Model, terdapat method __call() (yang di pangil dari object nya) dan __callStatic() (langsung dari class nya)
     * ● Magic Method ini sudah pernah kita bahas di kelas PHP OOP
     * ● https://www.php.net/manual/en/language.oop5.magic.php
     *
     * note: magic method
     *  __call() (yang di pangil dari object nya) --> panggil dari object model yang instance new Object()
     *  __callStatic() (langsung dari class nya) --> panggil dari object model static method
     *
     */

    public function testMethodMagic(){

        // __callStatic()
        Category::query()->where();
        Category::where();

    }

}
