<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 10; $i++) {
            // insert data ke table buku menggunakan Faker
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $faker->sha1,
                'alamat' => $faker->city(),
                'no_telepon' => $faker->phoneNumber(),
            ]);
        }
    }
}
