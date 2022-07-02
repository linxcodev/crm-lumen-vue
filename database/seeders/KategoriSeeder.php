<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kategori')->insert([
            ['key' => 'cash', 'value' => 'Cash', 'jenis' => 'tipe_pembayaran'],
            ['key' => 'bank_transfer', 'value' => 'Bank transfer', 'jenis' => 'tipe_pembayaran'],
        ]);
    }
}
