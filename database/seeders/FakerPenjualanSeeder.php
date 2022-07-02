<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
//        $productDetails = \App\Models\ProductsModel::where('id', '=', $productId)->get();
        $productDetails = \App\Models\ProdukModel::all();

            foreach($productDetails as $detail) {
                $sales = [
                    'nama' => $faker->name,
                    'quantity' => rand(1,10),
                    'id_produk' => $detail->id,
                    'tangal_pembayaran' => $faker->dateTimeThisMonth(),
                    'harga' => $detail->harga, //update this manual
                    'created_at' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
                    'updated_at' => \Carbon\Carbon::now(),
                    'dibuat_oleh' => 1
                ];

                DB::table('penjualan')->insert($sales);
            }
        }
}
