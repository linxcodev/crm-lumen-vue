<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FakerKeuanganSeeder extends Seeder
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
        $rowRand = rand(20,60);
        $category = ['steady income', 'large order', 'small order', 'one-off order'];
        $type = ['Invoice', 'proforma invoice', 'advance', 'simple transfer'];

        for ($i = 0; $i<$rowRand; $i++) {
            $rand = rand(800,2000);
            $calculateFromFakeRand = new \App\Services\KeuanganService();

            $data = $calculateFromFakeRand->loadCalculateNetAndVatByGivenGross($rand);

            $keuangan = [
                'nama' => $faker->name,
                'deskripsi' => 'test_desc',
                'kategori' => Arr::random($category),
                'type' => Arr::random($type),
                'gross' => $rand,
                'vat' => $data['vat'],
                'net' => $data['net'],
                'date' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
                'id_perusahaan' => $faker->randomElement($companiesIds),
                'created_at' => \Carbon\Carbon::now()->subDays(rand(1, 33)),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

            DB::table('keuangan')->insert($keuangan);
        }
    }
}
