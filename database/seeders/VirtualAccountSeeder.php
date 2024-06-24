<?php

namespace Database\Seeders;

use App\Models\VirtualAccount;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirtualAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // first() // jika datanya tidak ada maka akan null
        // firstOrFail() // jika datanya tidak ada maka akan batalkan
        // sql: select * from `wallets` where `customer_id` = ? limit 1
        $wallet = Wallet::query()->where("customer_id", "BUDHI")->firstOrFail();

        $virtualAccount = new VirtualAccount();
        $virtualAccount->bank = "BCA";
        $virtualAccount->va_number = "2222333344";
        $virtualAccount->wallet_id = $wallet->id;
        $virtualAccount->save();
    }
}
