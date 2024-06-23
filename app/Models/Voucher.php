<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Voucher extends Model
{
    use HasUuids; // kita gunakan trait HasUuids untuk handle primary key UUID di model/entity laravel
    use SoftDeletes; // kita gunakan trait SoftDeletes untuk  impl softDelete pada model/entity laravel.. akan handle query dan soft_delete update column "deleted_at"

    // secara default UUID hanya mengebalikan primaryKey saja.. tetapi kita bisa costom untuk salah satu column yang mau kita set UUID juga
    // overrid method uniqueIds() dari trait HasUuids
    public function uniqueIds()
    {
        return [$this->primaryKey, "voucher_code"]; // tambahkan name_column_table pada array yang mau di set UUID juga
    }

    protected $table = 'vouchers'; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = 'id'; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = 'string'; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = false; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate auto_create dan auto_update // default model laravel adalah true

    /**
     * buat query local scope
     *  ● Untuk membuat Local Scope, kita bisa membuat method di Model dengan awalan scope lalu diikuti
     *    dengan nama scope nya, misal scopeActive(), scopeNonActive(), dan lain-lain
     *  ● Method untuk Local Scope memerlukan parameter Builder yang bisa kita gunakan untuk
     *    menggunakan menambah kondisi
     */

    public function scopeActive(Builder $builder): void
    {
        $builder->where('is_active', true);
    }

    public function scopeNonActive(Builder $builder): void
    {
        $builder->where('is_active', false);
    }

}
