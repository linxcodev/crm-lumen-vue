<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(AdminSeeder::class);
        // $this->call(FakerKlienSeeder::class);
        // $this->call(FakerPerusahaanSeeder::class);
        // $this->call(FakerKaryawanSeeder::class);
        // $this->call(FakerDealSeeder::class);
        // $this->call(FakerProdukSeeder::class);
        // $this->call(FakerPenjualanSeeder::class);
        // $this->call(FakerTaskSeeder::class);
        // $this->call(FakerKeuanganSeeder::class);
        // $this->call(SettingSeeder::class);

        $this->call(FakerPembayaranKlienSeeder::class);
        $this->call(KategoriSeeder::class);
    }
}
