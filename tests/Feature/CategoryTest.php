<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

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

}
