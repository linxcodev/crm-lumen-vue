<?php

namespace App\Models;

use App\Services\KeuanganService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeuanganModel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'keuangan';

    public function companies()
    {
        return $this->belongsTo(PerusahaanModel::class, 'id_perusahaan');
    }

    public function storeFinance(array $requestedData, int $adminId) : int
    {
        $financesHelper = new KeuanganService();
        $dataToInsert = $financesHelper->loadCalculateNetAndVatByGivenGross($requestedData['gross']);

        return $this->insertGetId(
            [
                'nama' => $requestedData['nama'],
                'deskripsi' => $requestedData['deskripsi'],
                'kategori' => $requestedData['kategori'],
                'type' => $requestedData['type'],
                'gross' => $requestedData['gross'],
                'net' => $dataToInsert['net'],
                'vat' => $dataToInsert['vat'],
                'date' => $requestedData['date'] ?? date('Y-m-d'),
                'id_perusahaan' => $requestedData['id_perusahaan'],
                'created_at' => date('Y-m-d'),
                'is_active' => true,
                'dibuat_oleh' => $adminId
            ]
        );
    }

    public function updateFinance(int $financeId, array $requestedData) : int
    {
        $financesHelper = new KeuanganService();
        $dataToInsert = $financesHelper->loadCalculateNetAndVatByGivenGross($requestedData['gross']);

        return $this->where('id', $financeId)->update(
            [
                'nama' => $requestedData['nama'],
                'deskripsi' => $requestedData['deskripsi'],
                'type' => $requestedData['type'] ?? null,
                'kategori' => $requestedData['kategori'],
                'gross' => $requestedData['gross'],
                'net' => $dataToInsert['net'],
                'vat' => $dataToInsert['vat'],
                'date' => $requestedData['date'],
                'id_perusahaan' => $requestedData['id_perusahaan'],
                'updated_at' => date('Y-m-d'),
                'is_active' => 1
            ]
        );
    }

    public function setActive(int $financeId, int $activeType) : int
    {
        return $this->where('id', '=', $financeId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => date('Y-m-d')
            ]
        );
    }

    public function countFinances() : int
    {
        return $this->get()->count();
    }

    public function getPluckCompanies()
    {
        return PerusahaanModel::pluck('nama', 'id');
    }

    public function getFinancesSortedByCreatedAt()
    {
        return $this->all()->sortByDesc('created_at');
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
}
