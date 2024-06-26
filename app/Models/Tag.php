<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $table = 'tags'; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = 'id'; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = 'string'; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = false; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate auto_create dan auto_update // default model laravel adalah true


    // buat method untuk polymorphic
    // jadi tables tags bisa menyimpan many FK dari table products dan vouchers
    // type method yang dibuat harus return MorphToMany // karna table tags, table pivot taggable sebagai wadah PK/FK dari table tags, products, dan vouchers
    // morphedByMany()  // karna table tags sebagai wadah PK/FK dari table tags, products, dan vouchers
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, "taggable");
    }

    public function vouchers(): MorphToMany
    {
        return $this->morphedByMany(Voucher::class, "taggable");
    }

}
