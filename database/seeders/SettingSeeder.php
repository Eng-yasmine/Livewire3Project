<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(['email'=>'info@digitalagency.com'],[
            'name'=>'Digital Agency',
            'address'=>'        New Cairo, Egypt',
            'phone'=>'01010101010',
            'email'=>'info@digitalagency.com',
            
        ]);
    }
}
