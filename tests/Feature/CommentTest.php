<?php

namespace Tests\Feature;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CommentTest extends TestCase
{

    /**
     * Timestamps
     * ● Di materi Model, kita sudah bahas sedikit tentang attribute $timestamps di Model yang jika
     *   menggunakan value true, maka secara otomatis Eloquent akan menambahkan attribute created_at
     *   dan updated_at pada Model
     * ● Yang artinya kita harus membuat kolom tersebut
     * ● Namun, di Migrations, kita bisa menggunakan tipe timestamps() untuk membuat hal itu secara
     *   otomatis
     *
     * // buat model, migration, seeder
     * php artisan make:model Comment -m -s
     *
     * // set migration
     * // set model
     */

    public function testCreateComment(){

        $comment = new Comment();
        $comment->email = "budioct@test.com";
        $comment->title = "Sample title";
        $comment->comment = "Sample comment";
        // untuk created_at dan updated_at tidak perlu di set karna laravel akan auto generate..
        // untuk pertama kali data di buat ke 2 column ini akan di set datanyaa
        // jika datanya di update maka updated_at saja yang di set
        // $comment->created_at = new \DateTime(); // untuk waktu di laravel bisa menggunakan  Carbon::now(); dan new \DateTime()
        // $comment->updated_at = new \DateTime();

        // sql: insert into `comments` (`email`, `title`, `comment`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)
        $result = $comment->save();

        self::assertTrue($result);
        self::assertNotNull($comment->id);
        Log::info(json_encode($comment));

        /**
         * result:
         * [2024-06-22 06:13:27] testing.INFO: insert into `comments` (`email`, `title`, `comment`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)
         * [2024-06-22 06:13:27] testing.INFO: {"email":"budioct@test.com","title":"Sample title","comment":"Sample comment","updated_at":"2024-06-22T06:13:27.000000Z","created_at":"2024-06-22T06:13:27.000000Z","id":1}
         * */

    }

}
