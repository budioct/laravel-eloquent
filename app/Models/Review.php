<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'reviews'; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = 'id'; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = 'int'; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = true; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate auto_create dan auto_update // default model laravel adalah true


    // balikan dari
    // table products one to many reviews
    public function product(): BelongsTo{
        // belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
        // $related:    Product::class // model/entity yang berelasi
        // $foreignKey: product_id (FK) di table reviews
        // $ownerKey:   id PK dari table products reference product_id (FK) di table reviews
        return $this->belongsTo(Product::class, "product_id", "id"); // CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    }

    // balikan dari
    // table customers one to many reviews
    public function customer(): BelongsTo{
        // belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
        // $related:    Customer::class // model/entity yang berelasi
        // $foreignKey: customer_id (FK) di table customers
        // $ownerKey:   id PK dari table customers reference customer_id (FK) di table reviews
        return $this->belongsTo(Customer::class,"customer_id", "id"); // CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
    }

}
