<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createCommentsForProduct();
        $this->createCommentsForVoucher();
    }

    private function createCommentsForProduct() : void
    {
        $product = Product::find("1");

        $comment = new Comment();
        $comment->email = "budhi@test.com";
        $comment->title = "Title";
        $comment->commentable_id = $product->id;
        // $comment->commentable_type = Product::class;
        $comment->commentable_type = 'product'; // alias dari model Product::class
        $comment->save();
    }

    private function createCommentsForVoucher():void
    {
        $voucher = Voucher::first();

        $comment = new Comment();
        $comment->email = "budhi@test.com";
        $comment->title = "Title";
        $comment->commentable_id = $voucher->id;
        // $comment->commentable_type = Voucher::class;
        $comment->commentable_type = 'voucher'; // alias dari model Voucher::class
        $comment->save();
    }

}
