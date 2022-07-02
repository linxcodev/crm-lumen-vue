<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $employeeIds = \App\Models\KaryawanModel::all()->pluck('id')->toArray();
        $rowRand = rand(20,50);

        for ($i = 0; $i<$rowRand; $i++) {
            $tasks = [
                'nama' => 'test_task',
                'id_karyawan' => $faker->randomElement($employeeIds),
                'durasi' => rand(1,30),
                'completed' => rand(0,1),
                'created_at' => $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now'),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

            DB::table('task')->insert($tasks);
        }
    }
}
