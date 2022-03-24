<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->truncate();

        DB::table('users')->insert([
            'name' => 'admin',
            'role' => 1,
            'sex' => 1,
            'active' => 1,
            'email' => 'duongkorea193@gmail.com',
            'password' => Hash::make('123456'),
        ]); 
    }
}
