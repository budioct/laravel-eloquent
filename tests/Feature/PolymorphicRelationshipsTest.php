<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PolymorphicRelationshipsTest extends TestCase
{
    /**
     * Polymorphic Relationships
     * ● Polymorphic Relationship adalah relasi antar tabel namun relasinya bisa berbeda Model
     * ● Relasi ini sebenarnya tidak standard dalam relational database, karena dalam relational database,
     *   satu kolom FK hanya bisa mengacu ke satu tabel, sedangkan pada Polymorphic Relationship, satu
     *   kolom FK bisa mengacu ke beberapa tabel yang berbeda, tergantung tipe relasi nya
     * ● Karena itu, sebenarnya relasi Polymorphic sendiri tidak saya anjurkan untuk terlalu banyak
     *   digunakan, kecuali dalam keadaan tertentu
     * ● Dan karena relational database tidak mendukung satu kolom FK untuk lebih dari satu tabel, oleh
     *   karena itu implementasi Polymorphic Relationship ini tidak akan menggunakan foreign key di database
     * ● https://stackoverflow.com/questions/441001/possible-to-do-a-mysql-foreign-key-to-one-of-two-possible-tables
     *
     * Jenis Polymorphic Relationships
     * ● Laravel mendukung banyak Polymorphic Relationships, yaitu
     * ● One to One Polymorphic
     * ● One to Many Polymorphic
     * ● One of Many Polymorphic
     * ● Many to Many Polymorphic
     *
     */
}
