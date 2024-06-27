<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    protected $table = 'products'; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = 'id'; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = 'string'; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = false; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate auto_create dan auto_update // default model laravel adalah true

    // Hidden Attributes saat di akses modelnya karena data nya sudah di tampilkan pada object relasi
    protected $hidden = [
      "category_id"
    ];

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
        // using(model_extends_pivot_class): supaya pivot yang di akses langsung menjadi object model {}, tidak lagi menjadi array object [{},{}]
        return $this->belongsToMany(Customer::class, "customers_likes_products", "product_id", "customer_id")
            ->withPivot("created_at")
            ->using(Like::class);
    }

    // balikan Polymorphic dari model Image
    public function image(): MorphOne
    {
        // morphOne($related, $name, $type = null, $id = null, $localKey = null) // morphOne() untuk implementasi One to One Polymorphic
        // $related:  relasi yang berhunbungan implementasi Polymorphic
        // $name:     nama  method yang digunakan untuk relasi Polymorphic
        return $this->morphOne(Image::class, "imageable");
    }

    // balikan Polymorphic dari model Comment
    public function comments(): MorphMany
    {
        // morphMany($related, $name, $type = null, $id = null, $localKey = null) // MorphMany() untuk implementasi One to Many Polymorphic
        // $related:  relasi yang berhunbungan implementasi Polymorphic
        // $name:     nama  method yang digunakan untuk relasi Polymorphic
        return $this->morphMany(Comment::class, "commentable");
    }

    // contoh One of Many Polymorphic, Relasi One of Many Polymorphic juga mendukung penambahan kondisi,
    public function latestComment(): MorphOne
    {
        return $this->morphOne(Comment::class, "commentable")
            ->latest("created_at"); // latest(column_table) // ambil data paling baru
    }

    public function oldestComment(): MorphOne
    {
        return $this->morphOne(Comment::class, "commentable")
            ->oldest("created_at"); // oldest(column_table) // ambil data paling lama
    }

    // balikan Polymorphic dari model Tag
    public function tags(): MorphToMany
    {
        // morphToMany($related, $name, $type = null, $id = null, $localKey = null) // MorphMany() untuk implementasi One to Many Polymorphic
        // $related:  relasi yang berhunbungan implementasi Polymorphic
        // $name:     nama table pivot digunakan untuk relasi Polymorphic
        return $this->morphToMany(Tag::class, "taggable");
    }

}
