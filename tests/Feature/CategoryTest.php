<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

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

        Log::info(json_encode($category));

        /**
         * result:
         * [2024-06-19 06:24:25] testing.INFO: insert into `categories` (`id`, `name`) values (?, ?)
         * [2024-06-19 06:24:25] testing.INFO: {"id":"GADGET","name":"Gadget"}
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

    /**
     * Insert Many
     * ● Saat kita membuat aplikasi, kadang ada kasus dimana kita harus melakukan insert data Model lebih
     *   dari satu, atau disebut bulk / batch
     * ● Pada kasus ini, kita tidak perlu membuat object dari Model, kita cukup gunakan Query Builder
     *   untuk melakukan insert banyak data sekaligus menggunakan array
     */

    public function testInsertManyCategory(){

        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i",
            ]; // data di sisipkan ke array $categories[]
        }

        // sql: insert into `categories` (`id`, `name`) values (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)
        // insert dengan 2 cara
        //Category::query()->insert($categories); // insert(array $values) // insert lebih dari 1 data (multiple)
        $result = Category::insert($categories); // hasil return bollean

        self::assertTrue($result);

        foreach ($categories as $category) {
            Log::info(json_encode($category));
        }

        // sql: select count(*) as aggregate from `categories`
        // check jumlah total data yang berhasil di insert ke table
        // $total_data = Category::query()->count(); // count($columns = '*'): int // get total data dalam table
        $total = Category::count();

        self::assertEquals(10, $total);

        /**
         * result:
         * [2024-06-19 06:22:59] testing.INFO: insert into `categories` (`id`, `name`) values (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 0","name":"Name 0"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 1","name":"Name 1"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 2","name":"Name 2"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 3","name":"Name 3"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 4","name":"Name 4"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 5","name":"Name 5"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 6","name":"Name 6"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 7","name":"Name 7"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 8","name":"Name 8"}
         * [2024-06-19 06:22:59] testing.INFO: {"id":"ID 9","name":"Name 9"}
         * [2024-06-21 09:33:42] testing.INFO: select count(*) as aggregate from `categories`
         */

    }

    /**
     * Find
     * ● Laravel menyediakan method dengan prefix find() di Query Builder untuk mendapatkan satu data
     *   menggunakan primary key
     * ● Ini lebih mudah dibanding melakukan select dimana mengembalikan data berupa array
     *
     * // select() akan return list array
     * // find() akan return object model/entity
     */

    public function testFindCategory(){

        // sql: insert into `categories` (`id`, `name`, `description`) values (?, ?, ?)
        $this->seed(CategorySeeder::class); // seed($class = 'Database\\Seeders\\DatabaseSeeder') // use seeder // jalankan migration Seeder di unit test

        // sql: select * from `categories` where `categories`.`id` = ? limit 1
        // ada 2 cara
        // $category = Category::query()->find("FOOD"); // find("PK"); // find data berdasarkan primary key
        $category = Category::find("FOOD");

        assertNotNull($category);

        self::assertEquals("FOOD", $category->id);
        self::assertEquals("Food", $category->name);
        self::assertEquals("Food Category", $category->description);

        Log::info(json_encode($category));

        /**
         * result:
         * [2024-06-21 09:51:52] testing.INFO: insert into `categories` (`id`, `name`, `description`) values (?, ?, ?)
         * [2024-06-21 09:51:52] testing.INFO: select * from `categories` where `categories`.`id` = ? limit 1
         * [2024-06-21 09:52:49] testing.INFO: {"id":"FOOD","name":"Food","description":"Food Category","created_at":"2024-06-21 16:52:49"}
         */

    }

}
