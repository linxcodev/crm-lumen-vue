<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';

    public static function getOption(string $jenis)
    {
        $query = self::select('key', 'value')->where('jenis', $jenis)->get();

        if($query) {
            return $query;
        } else {
            new \Exception('invalid jenis');
        }
    }
}
