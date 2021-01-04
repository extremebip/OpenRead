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
            ['genre_id' => 'G000001', 'genre_type' => 'Fantasy', 'description' => 'a'],
            ['genre_id' => 'G000002', 'genre_type' => 'Social', 'description' => 'a'],
            ['genre_id' => 'G000003', 'genre_type' => 'Romantic', 'description' => 'a'],
            ['genre_id' => 'G000004', 'genre_type' => 'Horror', 'description' => 'a'],
            ['genre_id' => 'G000005', 'genre_type' => 'Thriller', 'description' => 'a'],
            ['genre_id' => 'G000006', 'genre_type' => 'Fanfiction', 'description' => 'a'],
            ['genre_id' => 'G000007', 'genre_type' => 'Science Fiction', 'description' => 'a'],
            ['genre_id' => 'G000008', 'genre_type' => 'History', 'description' => 'a'],
            ['genre_id' => 'G000009', 'genre_type' => 'Non-Fiction', 'description' => 'a'],
            ['genre_id' => 'G000010', 'genre_type' => 'Adventure', 'description' => 'a'],
        ]);
    }
}
