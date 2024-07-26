<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        if(DB::table('order_statuses')->count() == 0){
            DB::table("order_statuses")->insert([
                [
                    'name' => 'Pending',
                    'status' => '1',
                ],
                [
                    'name' => 'Processing',
                    'status' => '1',
                ],
				[
                    'name' => 'Cancelled',
                    'status' => '1',
                ],
                [
                    'name' => 'On The Way',
                    'status' => '1',
                ],
                [
                    'name' => 'Delivered',
                    'status' => '1',
                ],
                
                [
                    'name' => 'Returned',
                    'status' => '1',
                ],
				
				[
                    'name' => 'Refund',
                    'status' => '1',
                ]


            ]);
    }
}
}