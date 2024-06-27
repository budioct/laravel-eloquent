<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = "person"; // $table // deskripsi binding nama model pada nama table
    protected $primaryKey = "id"; // $primaryKey // deskripsi binding nama column primarykey pada table
    protected $keyType = "int"; // $keyType // deskripsi type data nama column primarykey
    public $incrementing = true; // $incrementing // deskripsi jika id type int / bigInt maka yang akan autoincrement pada table. default model laravel adalah true
    public $timestamps = false; // $timestamps adalah fitur laravel akan auto generate created_at dan updated_at // default model laravel adalah true

    // kita buat method yang handle Accessor dan Mutator
    // nanti yang kita panggil adalah attributenya,
    // ketika methodnya yang di buat camelCase aksesnya snake_case --> fullName : full_name
    // jika nanti mendetekasi ada Accessor dan Mutator model akan panggil fullName(): Attribute
    // yaitu Attribute menggunakan static method make seperti --> Attribute::make()
    // dan masukan named parameter get: callable dan set: callable seperti apa
    // get: untuk mendapatkan data seperti apa akses fullName
    // set: untuk mengubah data seperti apa akses fullName
    protected function fullName(): Attribute
    {
        // jadi kita buat Accessor dan Mutator
        // Accessor: ketika method ini di akses maka column first_name dan last_name akan digabung
        // Mutator: ketika datanya diubah dan di set ke method ini akan di pecah sesuai column table db first_name dan last_name
        return Attribute::make(
            get: function (): string {
                return $this->first_name . " " . $this->last_name;
            },
            set: function (string $value): array {
                $names = explode(" ", $value);
                return [
                    "first_name" => $names[0],
                    "last_name" => $names[1] ?? "",
                ];
            }
        );
    }

    // method ini digunakan untuk Accessor ketika column first_name di set maka akan di Set To Uppercase
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes): string {
                return strtoupper($value);
            },
            set: function ($value): array {
                return [
                    'first_name' => strtoupper($value)
                ];
            }
        );
    }

}
