<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{

    protected $table = "images"; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = "id"; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = "int"; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = true; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

    // buat method untuk polymorphic
    // jadi tables images bisa menyimpan 2 FK dari table customers dan products
    // type method yang dibuat harus return MorphTo // karna table images sebagai wadah FK dari table customers dan products
    // morphTo()  // karna table images sebagai wadah FK dari table customers dan products
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}
