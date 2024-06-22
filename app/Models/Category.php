<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * project laravel saat ini ver 9.x.x
     *
     * Model
     * ● Model adalah representasi dari Tabel di database
     * ● Model biasanya dibuat di folder app\Models
     * ● Dan Model adalah class turunan dari Illuminate\Database\Eloquent\Model
     * ● https://laravel.com/api/10.x/Illuminate/Database/Eloquent/Model.html
     *
     * Membuat Model
     * ● Kita tidak perlu membuat Model secara manual, kita bisa gunakan file artisan untuk membuat model
     * ● php artisan make:model NamaModel
     *   Membuat Model dengan Pendukungnya
     * ● Kadang, saat membuat Model, kita sering juga membuat fitur pendukung lainnya, seperti database
     *   migration atau database seeding
     * ● Kita tidak perlu melakukannya secara manual satu per satu, kita bisa sebutkan ketika membuat
     *   Model, sehingga otomatis akan dibuatkan Migrations dan Seeding nya
     * ● --migrations / -m ...untuk menambahkan Migrations
     * ● --seed / -s ...untuk menambahkan Seeder
     * note:
     * perintah laravel ver 10
     * ❯ php artisan make:model Category -migration --seed
     * perintah laravel ver 9
     * ❯ php artisan make:model Category -m -s
     *
     * Model Attributes
     * ● Setelah membuat Model, kita harus beri tahu informasi tentang schema tabel yang digunakan oleh
     *   Model
     * ● Kita bisa override attributes yang terdapat di class Model
     * ● Seperti $table, $primaryKey, $keyType, $incrementing dan lain-lain
     * ● https://laravel.com/api/10.x/Illuminate/Database/Eloquent/Model.html
     *
     * Timestamp
     * ● Secara default, Eloquent berharap terdapat kolom created_at dan updated_at sebagai informasi
     *   audit timestamp
     * ● Jika memang tidak membutuhkan informasi tersebut, kita bisa meng-override $timestamp menjadi
     *   false di Model
     *
     * Composite Primary Key
     * ● Sampai materi ini dibuat, sayangnya Laravel Eloquent tidak mendukung Composite Primary Key
     *   (primary key untuk lebih dari satu kolom)
     * ● Oleh karena itu, kita harus membuat satu kolom yang unique untuk primary key
     * ● Jika memang butuh composite key, kita bisa membuat dua kolom dengan index unique
     */

    protected $table = 'categories'; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = 'id'; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = 'string'; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = false; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate auto_create dan auto_update // default model laravel adalah true

    // $fillable adalah supaya allow Request $request masuk dari http request dan web request. tanpa harus binding data attribute model/entity dan request key
    // jadi ini bisa mempercepat pekerjaan di laravel
    protected $fillable = [
        "id",
        "name",
        "description",
    ];

}
