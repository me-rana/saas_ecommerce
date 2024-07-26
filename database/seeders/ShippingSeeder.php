<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        if(DB::table('shipping_charges')->count() == 0){
            DB::table('shipping_charges')->insert([
                [
                    'name' => 'Inside Dhaka',
                    'amount' =>  '50',
                    'status' => 1,
                ],
                [
                    'name' => 'Outside Dhaka',
                    'amount' =>  '120',
                    'status' => 1,
                ],

            ]);
        }
    }
}
