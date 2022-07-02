<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'nama' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ];

        DB::table('admin')->insert($admin);
    }
}
