<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Customer extends Model
{
    protected $table = "customers"; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = "id"; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = "string"; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = false; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

    // buat method untuk relasi ke table wallets relasi 1 ~ 1
    // dimana return value method HasOne (Punya satu), menentukan memiliki relasi satu
    // nama method bebas
    public function wallet(): HasOne
    {
        // hasOne($related, $foreignKey = null, $localKey = null)
        // $related:    Wallet::class // model/entity yang berelasi
        // $foreignKey: customer_id (FK) di table wallets
        // $localKey:   id PK dari table "customers"
        return $this->hasOne(Wallet::class, 'customer_id', 'id'); // CONSTRAINT `wallets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
    }

    // Has One Through (memiliki Satu Melalui)
    // skema relasi "customers" one to one "wallets" one to one "virtual_accouts"
    // secara tidak langsung customers punya relasi ke virtual_accouts dari wallets
    // jadi ingin langsung saja data dari customers query yang ada di virtual_accouts tanpa harus lewat wallets.. di sebut Has One Through
    public function virtualAccount(): HasOneThrough{
        return $this->hasOneThrough(
            VirtualAccount::class, // table virtual_accounts yang langsung relasi table customers
            Wallet::class, // table wallets yang di lewati
            "customer_id", // FK on wallets table
            "wallet_id", // FK on virtual_accounts table
            "id", // PK on customers table
            "id" // PK on wallets table
        );
    }

    // buat method untuk relasi customers 1 ~ Many ke table reviews
    // dimana return value method HasMany (Punya banyak), menentukan memiliki relasi banyak
    // nama method bebas
    public function reviews(): HasMany{
        // hasMany($related, $foreignKey = null, $localKey = null)
        // $related:    Review::class // model/entity yang berelasi
        // $foreignKey: customer_id (FK) di table products
        // $localKey:   id PK dari table "customers"
        return $this->hasMany(Review::class, "customer_id", "id");
    }

    // buat method untuk relasi many ~ many ke table products
    public function likeProducts(): BelongsToMany
    {
        // $related: table yang berlasi M to M
        // $table: table pivot untuk jembatan M to M
        // $foreignPivotKey: PK dan FK di table pivot dari table M to M (pertama)
        // $relatedPivotKey: PK dan FK di table pivot dari table M to M (kedua)
        return $this->belongsToMany(Product::class, "customers_likes_products", "customer_id", "product_id");
    }

}
