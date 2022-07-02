<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerusahaanModel extends Model
{
    use SoftDeletes;

    protected $table = 'perusahaan';
    protected $dates = ['deleted_at'];

    public function client()
    {
        return $this->belongsTo(KlienModel::class);
    }

    public function deals()
    {
        return $this->hasMany(DealModel::class, 'id');
    }

    public function employees_size()
    {
        return $this->belongsTo(KaryawanModel::class);
    }

    public function finances()
    {
        return $this->hasMany(KeuanganModel::class, 'id_perusahaan');
    }

    public function storeCompany(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'nama' => $requestedData['nama'],
                'nomor_pajak' => $requestedData['nomor_pajak'],
                'phone' => $requestedData['phone'],
                'kota' => $requestedData['kota'],
                'alamat_penagihan' => $requestedData['alamat_penagihan'],
                'negara' => $requestedData['negara'],
                'kode_pos' => $requestedData['kode_pos'],
                'jumlah_karyawan' => $requestedData['jumlah_karyawan'],
                'fax' => $requestedData['fax'],
                'deskripsi' => $requestedData['deskripsi'],
                'id_klien' => $requestedData['id_klien'],
                'created_at' => date('Y-m-d'),
                'is_active' => true,
                'dibuat_oleh' => $adminId
            ]
        );
    }

    public function updateCompany(int $companiesId, array $requestedData, $adminId) : int
    {
        return $this->where('id', '=', $companiesId)->update(
            [
                'nama' => $requestedData['nama'],
                'nomor_pajak' => $requestedData['nomor_pajak'],
                'phone' => $requestedData['phone'],
                'kota' => $requestedData['kota'],
                'alamat_penagihan' => $requestedData['alamat_penagihan'],
                'negara' => $requestedData['negara'],
                'kode_pos' => $requestedData['kode_pos'],
                'jumlah_karyawan' => $requestedData['jumlah_karyawan'],
                'fax' => $requestedData['fax'],
                'deskripsi' => $requestedData['deskripsi'],
                'id_klien' => $requestedData['id_klien'],
                'is_active' => true,
                'updated_at' => date('Y-m-d'),
                'diedit_oleh' => $adminId
            ]);
    }

    public function setActive(int $companiesId, int $activeType) : int
    {
       return $this->where('id', '=', $companiesId)->update(
           [
               'is_active' => $activeType
           ]
       );
    }

    public function countCompanies() : int
    {
        return $this->all()->count();
    }

    public function getCompaniesInLatestMonth() : float
    {
        $companiesCount = $this->where('created_at', '>=', Carbon::now()->subMonth())->count();
        $allCompanies = $this->all()->count();

        return ($allCompanies / 100) * $companiesCount;
    }

    public function getDeactivated() : int
    {
        return $this->where('is_active', '=', 0)->count();
    }

    public function getCompaniesSortedByCreatedAt()
    {
        return $this->all()->sortBy('created_at', 0, true)->slice(0, 5);
    }

    public function getPaginate()
    {
        $query = $this;
        
        if (request()->q != '') {
            $query = $query->where('nama_lengkap', 'LIKE', '%' . request()->q . '%');
        }

        $query = $query->paginate(SettingModel::getSettingValue('pagination_size'));

        return $query;
    }

    public function getCompany(int $companyId)
    {
        return $this::find($companyId);
    }

    public function pluckData()
    {
        return $this->select('nama', 'id')->get();
    }

    public function getAll()
    {
        return $this->all()->sortBy('created_at');
    }
}
