<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //pagination_size
        DB::table('setting')->insert(
            [
                'key' => 'pagination_size',
                'value' => 5,
                'deskripsi' => 'set pagination size'
            ]
        );

        //currency
        DB::table('setting')->insert(
            [
                'key' => 'currency',
                'value' => 'IDR',
                'deskripsi' => 'set currency type'
            ]
        );

        //priority_size
        DB::table('setting')->insert(
            [
                'key' => 'priority_size',
                'value' => 3,
                'deskripsi' => 'set priority size'
            ]
        );

        //invoice_tax
        DB::table('setting')->insert(
            [
                'key' => 'invoice_tax',
                'value' => 3,
                'deskripsi' => 'set invoice tax size'
            ]
        );

        //loading_circle
        DB::table('setting')->insert(
            [
                'key' => 'loading_circle',
                'value' => 1,
                'deskripsi' => 'set loading circle'
            ]
        );
    }
}
