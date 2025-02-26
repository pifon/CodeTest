<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                "name"=> "userOne",
                "email"=> "przemek@one.com",
                "email_verified_at"=> "2024-11-26 11:32:40",
                "password"=>  Hash::make('password'),
                "remember_token"=> null,
                "created_at"=> "2024-11-26 11:32:40",
                "updated_at"=> "2024-11-26 11:32:44"
            ],
            [
                "name"=> "userTwo",
                "email"=> "przemek@two.com",
                "email_verified_at"=> "2024-11-26 11:32:40",
                "password"=> Hash::make('password'),
                "remember_token"=> null,
                "created_at"=> "2024-11-26 11:32:40",
                "updated_at"=> "2024-11-26 11:32:44"
            ]
        ]);
    }
}
