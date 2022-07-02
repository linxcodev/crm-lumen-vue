<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerPembayaranKlienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $klienIds = \App\Models\KlienModel::all()->pluck('id')->toArray();
        $rowRand = rand(20,50);
        $date = date('Ymd');

        for ($i = 0; $i<$rowRand; $i++) {
            $tasks = [
                'invoice' =>  $date . $this->number((string) $i),
                'id_klien' => $faker->randomElement($klienIds),
                'tipe_pembayaran' => $faker->randomElement(['cash', 'bank_transfer']),
                'nominal' => rand(200000, 500000),
                'created_at' => $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now'),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

            DB::table('pembayaran_klien')->insert($tasks);
        }
    }

    public function number(string $i)
    {
        if (strlen($i) > 1) {
            return '00' . $i;
        }
        
        return '000' . $i;
    }
}
