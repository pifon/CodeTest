<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('games')->insert([
            // userTwo joined game
            [
                "x"=> "userOne",
                "o"=> "userTwo",
                "board"=> "[\"\",\"\",\"\",\"\",\"o\",\"\",\"\",\"\",\"\"]",
                "winner"=> null,
                "revenge"=> 0,
                "created_at"=> "2024-11-27 11:12:13",
                "updated_at"=> "2024-11-27 11:12:13"
            ],
            // userOne created game
            [
                "x"=> "userOne",
                "o"=> null,
                "board"=> "[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"]",
                "winner"=> null,
                "revenge"=> 0,
                "created_at"=> "2024-11-27 11:12:13",
                "updated_at"=> "2024-11-27 11:12:13"
            ],
            // Game in play
            [
                "x"=> "userOne",
                "o"=> "userTwo",
                "board"=> "[\"x\",\"o\",\"x\",\"\",\"o\",\"\",\"\",\"\",\"\"]",
                "winner"=> null,
                "revenge"=> 0,
                "created_at"=> "2024-11-27 11:12:13",
                "updated_at"=> "2024-11-27 11:12:13"
            ],
            // userOne won, no revenge created
            [
                "x"=> "userOne",
                "o"=> "userTwo",
                "board"=> "[\"x\",\"x\",\"x\",\"o\",\"o\",\"x\",\"\",\"o\",\"o\"]",
                "winner"=> "x",
                "revenge"=> 0,
                "created_at"=> "2024-11-27 11:12:13",
                "updated_at"=> "2024-11-27 11:12:13"
            ],
            // userOne won revenge id: 6
            [
                "x"=> "userOne",
                "o"=> "userTwo",
                "board"=> "[\"x\",\"x\",\"x\",\"o\",\"o\",\"x\",\"\",\"o\",\"o\"]",
                "winner"=> "x",
                "revenge"=> 6,
                "created_at"=> "2024-11-27 11:12:13",
                "updated_at"=> "2024-11-27 11:12:13"
            ],
            // Revenge game for id: 5
            [
                "x"=> "userOne",
                "o"=> "userTwo",
                "board"=> "[\"x\",\"\",\"x\",\"o\",\"o\",\"x\",\"\",\"o\",\"o\"]",
                "winner"=> null,
                "revenge"=> 0,
                "created_at"=> "2024-11-27 11:12:13",
                "updated_at"=> "2024-11-27 11:12:13"
            ]
            ]);
    }
}
