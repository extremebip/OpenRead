<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            ['genre_id' => 'GR00001', 'genre_type' => 'Fantasy', 'description' => 'a'],
            ['genre_id' => 'GR00002', 'genre_type' => 'Social', 'description' => 'a'],
            ['genre_id' => 'GR00003', 'genre_type' => 'Romantic', 'description' => 'a'],
            ['genre_id' => 'GR00004', 'genre_type' => 'Horror', 'description' => 'a'],
            ['genre_id' => 'GR00005', 'genre_type' => 'Thriller', 'description' => 'a'],
            ['genre_id' => 'GR00006', 'genre_type' => 'Fanfiction', 'description' => 'a'],
            ['genre_id' => 'GR00007', 'genre_type' => 'Science Fiction', 'description' => 'a'],
            ['genre_id' => 'GR00008', 'genre_type' => 'History', 'description' => 'a'],
            ['genre_id' => 'GR00009', 'genre_type' => 'Non-Fiction', 'description' => 'a'],
            ['genre_id' => 'GR00010', 'genre_type' => 'Adventure', 'description' => 'a'],
            ['genre_id' => 'GR00011', 'genre_type' => 'Comedy', 'description' => 'a'],
        ]);
    }
}
