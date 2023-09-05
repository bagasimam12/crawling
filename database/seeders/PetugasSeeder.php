<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            // insert data ke table buku menggunakan Faker
            DB::table('petugas')->insert([
                'name' => $faker->name,
                'jabatan' => $faker->word,
                'alamat' => $faker->city(),
                'no_telepon' => $faker->phoneNumber(),
                'jenis_kelamin' => $faker->randomElement(['laki-laki', 'perempuan']),
            ]);
        }
    }
}
