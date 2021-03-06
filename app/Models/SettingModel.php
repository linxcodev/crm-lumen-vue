<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
    protected $table = 'setting';

    public function updateSetting(string $key, string $value) : int
    {
        return $this->where('key', $key)->update(
            [
                'value' => $value,
                'updated_at' => date('Y-m-d')
            ]);
    }

    public static function getSettingValue(string $key)
    {
        $query = self::where('key', $key)->get()->last();

        if($query) {
            return $query->value;
        } else {
            new \Exception('invalid key');
        }
    }

    public function getAllSettings()
    {
        return $this->all()->toArray();
    }
}
