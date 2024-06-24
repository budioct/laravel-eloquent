<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VirtualAccount extends Model
{
    protected $table = "virtual_accounts"; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = "id"; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = "int"; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = true; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true

    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

    // balikan dari
    // table wallets one to one virtual_accounts
    public function wallet(): BelongsTo
    {
        // belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
        // $related:    Wallet::class // model/entity yang berelasi
        // $foreignKey: wallet_id (FK) di table virtual_accounts
        // $ownerKey:   id PK dari table wallets reference wallet_id (FK) di table virtual_accounts
        return $this->belongsTo(Wallet::class, "wallet_id", "id");
    }

}
