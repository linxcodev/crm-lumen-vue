<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $names = ['Photo camera', 'Underwear', 'Hoodie', 'Boots', 'Handkerchiefs', 'Wet wipes', 'Pen', 'Comb',
            'Food from the fridge', 'Card', 'Compass', 'GOT books', 'Slippers', 'Keys from the apartment', 'Cream',
            'Binoculars', 'Maps','beverages', 'Band', 'Toothpaste', 'Cape', 'Undershirt', 'Wallet', 'Snacks on the road',
            'Towel', 'Gloves', 'Socks', 'Trousers', 'Shampoo', 'Brush', 'Sleeping bag', 'Phone', 'Bags','Shower gel'];

        for ($i = 0; $i<=20; $i++) {
            $products = [
                'nama' => $names[$i],
                'kategori' => $faker->lastName,
                'stok' => rand(10,50),
                'harga' => rand(100000,900000),
                'created_at' => $faker->dateTimeBetween($startDate = '-'.$i.' days', $endDate = 'now'),
                'updated_at' => \Carbon\Carbon::now(),
                'dibuat_oleh' => 1
            ];

            DB::table('produk')->insert($products);
        }
    }
}
