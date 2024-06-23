<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = 'products'; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = 'id'; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = 'string'; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = false; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate auto_create dan auto_update // default model laravel adalah true


    // buat method untuk relasi kebalikan relasi 'categories' 1 ~ Many 'products'
    // untuk model pertama 'HasMany' dan model kedua 'BelongsTo'
    // dimana return value method BelongsTo (Milik)..
    // nama method bebas
    public function category(): BelongsTo
    {
        // belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
        // $related:    Category::class // model/entity yang berelasi
        // $foreignKey: category_id (FK) di table products
        // $ownerKey:   id PK dari table categories reference category_id (FK) di table products
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

}
