<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // di Model harus menggunakan trait HasFactory untuk memberitahu bahwa Model ini memiliki Factory
    use HasFactory; // gunakan trait HasFactory supaya model ini di kenali implementasi Factory

    protected $table = "employees"; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = "id"; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = "string"; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = false; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = true; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

}
