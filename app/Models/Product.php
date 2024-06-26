<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsTo(Category::class, 'category_id', 'id'); // CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    }

    // buat method untuk relasi products 1 ~ Many ke table reviews
    // dimana return value method HasMany (Punya banyak), menentukan memiliki relasi banyak
    // nama method bebas
    public function reviews(): HasMany {
        // hasMany($related, $foreignKey = null, $localKey = null)
        // $related:    Review::class // model/entity yang berelasi
        // $foreignKey: product_id (FK) di table products
        // $localKey:   id PK dari table "products"
        return $this->hasMany(Review::class, "product_id", "id");
    }

    // buat balikan method untuk relasi many ~ many ke table customers
    public function likeByCustomers(): BelongsToMany
    {
        // $related: table yang berlasi M to M
        // $table: table pivot untuk jembatan M to M
        // $foreignPivotKey: PK dan FK di table pivot dari table M to M (pertama)
        // $relatedPivotKey: PK dan FK di table pivot dari table M to M (kedua)
        // withPivot(name_column): supaya column ketika di query terbaca.. karna ini adalah column tambahan (costum)
        return $this->belongsToMany(Customer::class, "customers_likes_products", "product_id", "customer_id")
            ->withPivot("created_at");
    }

}
