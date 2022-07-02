<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealModel extends Model
{
    use SoftDeletes;

    protected $table = 'deal';
    protected $dates = ['deleted_at'];

    public function companies()
    {
        return $this->belongsTo(PerusahaanModel::class, 'id_perusahaan');
    }

    public function storeDeal(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'nama' => $requestedData['nama'],
                'waktu_mulai' => $requestedData['waktu_mulai'],
                'waktu_berakhir' => $requestedData['waktu_berakhir'],
                'id_perusahaan' => $requestedData['id_perusahaan'],
                'created_at' => date('Y-m-d'),
                'is_active' => true,
                'dibuat_oleh' => $adminId
            ]
        );
    }

    public function updateDeal(int $dealId, array $requestedData, $adminId) : int
    {
        return $this->where('id', '=', $dealId)->update(
            [
                'nama' => $requestedData['nama'],
                'waktu_mulai' => $requestedData['waktu_mulai'],
                'waktu_berakhir' => $requestedData['waktu_berakhir'],
                'id_perusahaan' => $requestedData['id_perusahaan'],
                'updated_at' => date('Y-m-d'),
                'diedit_oleh' => $adminId
            ]
        );
    }

    public function setActive(int $dealId, bool $activeType, $adminId) : int
    {
        return $this->where('id', '=', $dealId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => date('Y-m-d'),
                'diedit_oleh' => $adminId
            ]
        );
    }

    public function countDeals() : int
    {
        return $this->get()->count();
    }

    public static function getDealsInLatestMonth() {
        $dealsCount = self::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $allDeals = self::all()->count();

        return ($allDeals / 100) * $dealsCount;
    }

    public function getDeactivated() : int
    {
        return $this->where('is_active', '=', 0)->count();
    }

    public function getPluckCompanies()
    {
        return $this->pluck('nama', 'id');
    }

    public function getPaginate()
    {
        $query = $this->with('companies');
        
        if (request()->q != '') {
            $query = $query->where('nama', 'LIKE', '%' . request()->q . '%');
        }

        $query = $query->paginate(SettingModel::getSettingValue('pagination_size'));

        return $query;
    }

    public function getDeal(int $dealId) : self
    {
        return $this->with('companies')->find($dealId);
    }

    public function getName(int $dealId)
    {
        return $this->where('id', $dealId)->get()->last()->nama;
    }
}
