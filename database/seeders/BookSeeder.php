<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            // insert data ke table buku menggunakan Faker
            DB::table('book')->insert([
                'name' => $faker->sentence,
                'price' => $faker->randomNumber(6, true),
                'desc' => $faker->paragraph(5, true),
                'status' => $faker->randomElement(['new', 'second']),
            ]);
        }
    }
}
