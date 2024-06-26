<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Like extends Pivot
{
    /**
     * Pivot Model
     * ● Jika Intermediate Table memiliki kolom selain kolom untuk Join, kadang ada baiknya dibuat dalam
     *   bentuk Pivot Model
     * ● Pivot Model adalah Model seperti biasanya, hanya saja harus turunan dari Pivot, bukan Model
     * ● Salah satu kelebihan menambahkan Pivot Model adalah, kita bisa query data secara langsung lewat
     *   Pivot Model atau menambahkan relasi pada Pivot Model
     *
     * Pivot Class
     * ● Pivot Class sebenarnya adalah turunan dari Model class, oleh karena itu hampir semua yang bisa
     *   dilakukan di Model, bisa juga dilakukan di Pivot
     * ● Namun pada Pivot Class, secara default $incrementing bernilai false, jadi jika kita membuat Pivot
     *   Model dengan auto increment, maka kita harus mengubah nilai $incrementing nya menjadi true
     * ● Selain itu, Pivot Model tidak mendukung SoftDeletes, jika kita ingin menggunakan SoftDeletes, kita
     *   perlu mengubah Pivot Model, menjadi Model biasa pada Eloquent
     */

    protected $table = "customers_likes_products"; // $table // deskripsi binding nama model pada nama table
    protected $foreignKey = "customer_id"; // $foreignKey // deskripsi biding nama model dengan nama column table ini PK dari relasi many to many ke table pivot dari table customers
    protected $relatedKey = "product_id"; // $relatedKey // deskripsi biding nama model dengan nama column table ini PK dari relasi many to many ke table pivot dari table products

    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

    // karena Object Pivot akan check dia selalu apakah ada created_at dan updated_at..
    // dalam kasus ini kita tidak menggunakan $timestamps yang implementasi created_at dan updated_at, kita perlu non-aktifkan dengan method usesTimestamps(): bool
    // note: supaya tidak kena exception dari laravel kita perlu menonaktifkan updated_at
    public function usesTimestamps(): bool
    {
        return false;
    }

    // buat method supaya table pivot yang di akses menjadi object{}.. bukan lagi menjadi Array Object [{},{}]
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

    // buat method supaya table pivot yang di akses menjadi object{}.. bukan lagi menjadi Array Object [{},{}]
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }


}
