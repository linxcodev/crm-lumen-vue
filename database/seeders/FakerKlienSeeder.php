<?php
namespace Database\Seeders;

use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerKlienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $section = ['transport', 'logistic', 'finances'];

        for ($i = 0; $i<=10; $i++) {
            $client = [
                'nama_lengkap' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->companyEmail,
                'bagian' => Arr::random($section),
                'budget' => rand(100000, 900000),
                'lokasi' => $faker->country,
                'zip' => $faker->postcode,
                'kota' => $faker->city,
                'negara' => $faker->country,
                'created_at' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

             DB::table('klien')->insert($client);
        }
    }
}
