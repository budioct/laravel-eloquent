<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    protected $table = "wallets"; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = "id"; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = "int"; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = true; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

    // buat method untuk relasi kebalikan relasi 1 ~ 1
    // untuk model pertama 'HasOne' dan model kedua 'BelongsTo'
    // dimana return value method BelongsTo (Milik)..
    // nama method bebas
    public function customer(): BelongsTo
    {
        // belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
        // $related:    Customer::class // model/entity yang berelasi
        // $foreignKey: customer_id (FK) di table wallets
        // $ownerKey:   id PK dari table customers reference customer_id (FK) di table wallets
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

}
