<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $userIds = \App\Models\KlienModel::all()->pluck('id')->toArray();
        $rowRand = rand(10,20);

        for ($i = 0; $i<$rowRand; $i++) {
            $companies = [
                'nama' => $faker->company,
                'nomor_pajak' => $faker->unixTime($max = 'now'),
                'phone' => $faker->phoneNumber,
                'kota' => $faker->city,
                'alamat_penagihan' => $faker->streetAddress,
                'negara' => $faker->country,
                'kode_pos' => $faker->postcode,
                'jumlah_karyawan' => rand(100,1000),
                'fax' => $faker->phoneNumber,
                'deskripsi' => 'test deskripsi',
                'id_klien' => $userIds[array_rand($userIds)],
                'created_at' => \Carbon\Carbon::now()->subDays(rand(1, 33)),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

            DB::table('perusahaan')->insert($companies);
        }
    }
}
