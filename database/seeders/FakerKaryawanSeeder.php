<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerKaryawanSeeder extends Seeder
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
            $employees = [
                'nama_lengkap' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'pekerjaan' => 'engineer',
                'catatan' => 'test catatan',
                'id_klien' => $faker->randomElement($userIds),
                'created_at' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

            DB::table('karyawan')->insert($employees);
        }
    }
}
