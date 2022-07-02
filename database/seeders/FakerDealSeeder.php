<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $companiesIds = \App\Models\PerusahaanModel::all()->pluck('id')->toArray();
        $rowRand = rand(20,50);

        for ($i = 0; $i<$rowRand; $i++) {
            $deals = [
                'nama' => $faker->company,
                'waktu_mulai' => $faker->date,
                'waktu_berakhir' => $faker->date,
                'id_perusahaan' => $faker->randomElement($companiesIds),
                'created_at' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

            DB::table('deal')->insert($deals);
        }
    }
}
