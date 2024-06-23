<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        return $this->hasOne(Wallet::class, 'customer_id', 'id');
    }

}
