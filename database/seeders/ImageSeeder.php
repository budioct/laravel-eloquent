<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            $image = new Image();
            $image->url = "https://www.programmerzamannow.com/image/1.jpg";
            $image->imageable_id = "BUDHI";
            $image->imageable_type = Customer::class;
            $image->save();
        }
        {
            $image = new Image();
            $image->url = "https://www.programmerzamannow.com/image/2.jpg";
            $image->imageable_id = "1";
            $image->imageable_type = Product::class;
            $image->save();
        }
    }
}
