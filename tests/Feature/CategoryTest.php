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

        // insert multiple (lebih dari 1 data)

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


    /**
     * Update
     * ● Untuk melakukan update terhadap Model, kita bisa menggunakan method update() atau save()
     * ● Saat melakukan update, kita harus melakukan find() data terlebih dahulu, jadi bukan dengan
     *   membuat object Model baru dengan menggunakan kata kunci new
     * ● Jika pada kasus tertentu, kita akhirnya tidak bisa melakukan find(), dan terpaksa harus
     *   menggunakan kata kunci new, kita harus mengubah attribute $exists dari defaultnya false, menjadi
     *   true, untuk memberi tahu Laravel bahwa data object itu ada di database
     */

    public function testUpdateCategory(){

        // sql: insert into `categories` (`id`, `name`, `description`) values (?, ?, ?)
        $this->seed(CategorySeeder::class);

        // sql: select * from `categories` where `categories`.`id` = ? limit 1
        // $category = Category::query()->find("Food");
        $category = Category::find("Food");

        // update data
        $category->name = "Food Updated";
        $category->description = "Food Description Updated";

        // sql: update `categories` set `name` = ?, `description` = ? where `id` = ?
        $result = $category->update();

        self::assertTrue($result);

        Log::info($category);

        /**
         * result:
         * [2024-06-21 09:58:42] testing.INFO: insert into `categories` (`id`, `name`, `description`) values (?, ?, ?)
         * [2024-06-21 09:58:42] testing.INFO: select * from `categories` where `categories`.`id` = ? limit 1
         * [2024-06-21 09:58:42] testing.INFO: update `categories` set `name` = ?, `description` = ? where `id` = ?
         * [2024-06-21 09:58:42] testing.INFO: {"id":"FOOD","name":"Food Updated","description":"Food Description Updated","created_at":"2024-06-21 16:58:42"}
         */

    }


    /**
     * Select
     * ● Untuk melakukan select data yang datanya bisa lebih dari satu, maka kita bisa menggunakan Query
     *   Builder seperti biasanya, yang sudah kita bahas di materi Laravel Database
     *
     * Perhatikan
     * ● Saat kita melakukan select lebih dari satu data, hasil dari Query Builder adalah Collection dari
     *   Model nya
     * ● Jadi bukan hanya Collection Array
     * ● Artinya kita bisa melakukan operasi lainnya pada hasil select Model tersebut, misal melakukan
     *   update
     *
     * note: select()->get() akan return list object
     */

    public function testSelectCategory(){

        for ($i = 0; $i < 5; $i++) {
        // sql: insert into `categories` (`id`, `name`) values (?, ?) // insert data 5x
            $category = new Category();
            $category->id = "FOOD $i";
            $category->name = "Food $i";
            $category->save(); // save() // eksekusi simpan
        }

        // sql: select * from `categories` where `description` is null
        // $category = Category::query()
        //    ->whereNull("description")
        //    ->get(); // akan dapat semua data dalam bentuk list array object [{},{}]

        // sql: select `id`, `name` from `categories` where `description` is null
        $category = Category::query()
            ->select('id', 'name')
            ->whereNull('description')
            ->get(); // akan dapat semua data dalam bentuk list array object [{},{}]

        self::assertEquals(5, $category->count());

        $category->each(function ($item){
            self::assertNull($item->description);
            Log::info($item);
        });

        /**
         * result:
         * [2024-06-21 10:09:54] testing.INFO: select * from `categories` where `description` is null
         * [2024-06-21 10:09:54] testing.INFO: {"id":"FOOD 0","name":"Food 0","description":null,"created_at":"2024-06-21 17:09:54"}
         * [2024-06-21 10:09:54] testing.INFO: {"id":"FOOD 1","name":"Food 1","description":null,"created_at":"2024-06-21 17:09:54"}
         * [2024-06-21 10:09:54] testing.INFO: {"id":"FOOD 2","name":"Food 2","description":null,"created_at":"2024-06-21 17:09:54"}
         * [2024-06-21 10:09:54] testing.INFO: {"id":"FOOD 3","name":"Food 3","description":null,"created_at":"2024-06-21 17:09:54"}
         * [2024-06-21 10:09:54] testing.INFO: {"id":"FOOD 4","name":"Food 4","description":null,"created_at":"2024-06-21 17:09:54"}
         */

    }

    public function testSelectCategoryLaluDiUpdateLagi(){

        for ($i = 0; $i < 5; $i++) {
            // sql: insert into `categories` (`id`, `name`) values (?, ?) // insert data 5x
            $category = new Category();
            $category->id = "FOOD $i";
            $category->name = "Food $i";
            $category->save(); // save() // eksekusi simpan
        }

        // sql: select * from `categories` where `description` is null
        // $category = Category::query()
        //    ->whereNull("description")
        //    ->get(); // akan dapat semua data dalam bentuk list array object [{},{}]

        // sql: select `id`, `name` from `categories` where `description` is null
        $category = Category::query()
            ->select('id', 'name')
            ->whereNull('description')
            ->get(); // akan dapat semua data dalam bentuk list array object [{},{}]

        self::assertEquals(5, $category->count());

        $category->each(function ($item){
            self::assertNull($item->description);

            // karna yang kita dapat adalah list object bukan list collection.. kita masih bisa update datanya
            // seperti ini
            $item->description = "Updated Broo";

            // sql: update `categories` set `description` = ? where `id` = ?
            $item->update();

            Log::info($item);
        });

        /**
         * result:
         * [2024-06-21 10:20:34] testing.INFO: select `id`, `name` from `categories` where `description` is null
         * [2024-06-21 10:20:34] testing.INFO: update `categories` set `description` = ? where `id` = ?
         * [2024-06-21 10:20:34] testing.INFO: {"id":"FOOD 0","name":"Food 0","description":"Updated Broo"}
         * [2024-06-21 10:20:34] testing.INFO: update `categories` set `description` = ? where `id` = ?
         * [2024-06-21 10:20:34] testing.INFO: {"id":"FOOD 1","name":"Food 1","description":"Updated Broo"}
         * [2024-06-21 10:20:34] testing.INFO: update `categories` set `description` = ? where `id` = ?
         * [2024-06-21 10:20:34] testing.INFO: {"id":"FOOD 2","name":"Food 2","description":"Updated Broo"}
         * [2024-06-21 10:20:34] testing.INFO: update `categories` set `description` = ? where `id` = ?
         * [2024-06-21 10:20:34] testing.INFO: {"id":"FOOD 3","name":"Food 3","description":"Updated Broo"}
         * [2024-06-21 10:20:34] testing.INFO: update `categories` set `description` = ? where `id` = ?
         * [2024-06-21 10:20:34] testing.INFO: {"id":"FOOD 4","name":"Food 4","description":"Updated Broo"}
         */

    }


    /**
     * Update Many
     * ● Pada kasus misal kita mau melakukan update yang bisa berdampak ke lebih dari satu data, kita
     *   tidak perlu melakukan update satu per satu ke object model nya
     * ● Kita bisa menggunakan Query Builder
     */

    public function testUpdateManyCategory(){

        // update multiple (lebih dari 1 data)

        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i",
            ]; // data di sisipkan ke array $categories[]
        }

        // sql: insert into `categories` (`id`, `name`) values (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)
        $result = Category::query()->insert($categories);
        //$result = Category::insert($categories);

        self::assertTrue($result);
        foreach ($categories as $category) {
            Log::info(json_encode($category));
        }

        // sql: update `categories` set `description` = ? where `description` is null
        Category::query()->whereNull("description")->update([
            "description" => "Updated"
        ]); // update(array $values): int // update lebih dari satu data

        // sql: select * from `categories` where `description` = ?
        $list = Category::query()->where("description", "=", "Updated")->get();

        self::assertEquals(5, $list->count());
        $list->each(function ($item){
            Log::info(json_encode($item));
        });

        /**
         * result:
         * [2024-06-21 15:35:58] testing.INFO: insert into `categories` (`id`, `name`) values (?, ?), (?, ?), (?, ?), (?, ?), (?, ?)
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 0","name":"Name 0"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 1","name":"Name 1"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 2","name":"Name 2"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 3","name":"Name 3"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 4","name":"Name 4"}
         * [2024-06-21 15:35:58] testing.INFO: update `categories` set `description` = ? where `description` is null
         * [2024-06-21 15:35:58] testing.INFO: select * from `categories` where `description` = ?
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 0","name":"Name 0","description":"Updated","created_at":"2024-06-21 22:35:58"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 1","name":"Name 1","description":"Updated","created_at":"2024-06-21 22:35:58"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 2","name":"Name 2","description":"Updated","created_at":"2024-06-21 22:35:58"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 3","name":"Name 3","description":"Updated","created_at":"2024-06-21 22:35:58"}
         * [2024-06-21 15:35:58] testing.INFO: {"id":"ID 4","name":"Name 4","description":"Updated","created_at":"2024-06-21 22:35:58"}
         */

    }


    /**
     * Delete
     * ● Untuk melakukan delete data, kita bisa menggunakan method delete() di object Model yang sudah
     *   kita buat
     * ● Untuk menggunakan delete(), kita harus melakukan find() data terlebih dahulu
     * ● Sama seperti update data, jika kita terpaksa harus melakukan delete dengan membuat Model
     *   dengan kata kunci new, kita harus mengubah attribute $exists dari false menjadi true
     */

    public function testDeleteCategory(){

        // sql: insert into `categories` (`id`, `name`, `description`) values (?, ?, ?)
        $this->seed(CategorySeeder::class);

        // sql: select * from `categories` where `categories`.`id` = ? limit 1
        $category = Category::query()->find("Food"); // find berdasarkan primary key id

        // sql: delete from `categories` where `id` = ?
        $result = $category->delete(); // delete() // hapus data
        self::assertTrue($result);

        // sql: select count(*) as aggregate from `categories`
        $total = Category::query()->count();
        self::assertEquals(0, $total);

        /**
         * result:
         * [2024-06-21 15:42:16] testing.INFO: insert into `categories` (`id`, `name`, `description`) values (?, ?, ?)
         * [2024-06-21 15:42:16] testing.INFO: select * from `categories` where `categories`.`id` = ? limit 1
         * [2024-06-21 15:42:16] testing.INFO: delete from `categories` where `id` = ?
         * [2024-06-21 15:42:16] testing.INFO: select count(*) as aggregate from `categories`
         */

    }

}
