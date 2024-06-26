<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag = new Tag();
        $tag->id = "aom";
        $tag->name = "Anak Om Mamat";
        $tag->save();

        $product = Product::find("1");
        $product->tags()->attach($tag); // attach() // add data ke table pivot

        $voucher = Voucher::first();
        $voucher->tags()->attach($tag); // attach() // add data ke table pivot
    }
}
