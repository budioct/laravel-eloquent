<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $table = "comments"; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = "id"; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = "int"; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = true; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = true; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

    // Default Attribute Values..
    // nantinya jika column title dan comment pada table comments tidak di set..
    // maka akan di set $attributes yang jalankan dengan value default nya
    protected $attributes = [
        "title" => "Sample Title",
        "comment" => "Sample Comment",
    ];

    // buat method untuk polymorphic
    // jadi tables comments bisa menyimpan 2 FK dari table products dan vouchers
    // type method yang dibuat harus return MorphTo // karna table comments sebagai wadah FK dari table products dan vouchers
    // morphTo()  // karna table comments sebagai wadah FK dari table products dan vouchers
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
