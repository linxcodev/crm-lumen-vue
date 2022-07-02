<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class KaryawanModel extends Model
{
    use SoftDeletes;

    protected $table = 'karyawan';
    protected $dates = ['deleted_at'];

    public function companies()
    {
        return $this->hasMany(PerusahaanModel::class);
    }

    public function deals()
    {
        return $this->belongsTo(DealModel::class);
    }

    public function client()
    {
        return $this->belongsTo(KlienModel::class, 'id_klien');
    }

    public function tasks()
    {
        return $this->hasMany(TaskModel::class, 'id_karyawan');
    }

    public function storeEmployee(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'nama_lengkap' => $requestedData['nama_lengkap'],
                'phone' => $requestedData['phone'],
                'email' => $requestedData['email'],
                'pekerjaan' => $requestedData['pekerjaan'],
                'catatan' => $requestedData['catatan'],
                'id_klien' => $requestedData['id_klien'],
                'created_at' => date('Y-m-d'),
                'is_active' => true,
                'dibuat_oleh' => $adminId
            ]
        );
    }

    public function updateEmployee(int $employeeId, array $requestedData, int $adminId) : int
    {
        return $this->where('id', '=', $employeeId)->update(
            [
                'nama_lengkap' => $requestedData['nama_lengkap'],
                'phone' => $requestedData['phone'],
                'email' => $requestedData['email'],
                'pekerjaan' => $requestedData['pekerjaan'],
                'catatan' => $requestedData['catatan'],
                'id_klien' => $requestedData['id_klien'],
                'updated_at' => date('Y-m-d'),
                'diedit_oleh' => $adminId
            ]
        );
    }

    public function setActive(int $employeeId, int $activeType) : int
    {
        return $this->where('id', '=', $employeeId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => date('Y-m-d')
            ]
        );
    }

    public function countEmployees() : int
    {
        return $this->all()->count();
    }

    public function getEmployeesInLatestMonth() : float
    {
        $employeesCount = $this->where('created_at', '>=', Carbon::now()->subMonth())->count();
        $allEmployees = $this->all()->count();

        return ($allEmployees / 100) * $employeesCount;
    }

    public function getDeactivated() : int
    {
        return $this->where('is_active', '=', 0)->count();
    }

    public function getEmployees()
    {
        $query = $this->all()->sortBy('created_at');

        foreach($query as $key => $value) {
            $query[$key]->is_active = $query[$key]->is_active  ? 'Active' : 'Deactivate';
            Arr::add($query[$key], 'taskCount', $this->getEmployeesTaskCount($value->id));
        }

        return $query;
    }

    public function getPaginate()
    {
        $query = $this->with('client');
        
        if (request()->q != '') {
            $query = $query->where('nama_lengkap', 'LIKE', '%' . request()->q . '%');
        }

        $query = $query->paginate(SettingModel::getSettingValue('pagination_size'));

        foreach($query as $key => $value) {
            Arr::add($query[$key], 'taskCount', $this->getEmployeesTaskCount($value->id));
        }

        return $query;
    }

    public function getEmployeeDetails(int $employeeId) : self
    {
        $query = $this->with('client')->find($employeeId);

        Arr::add($query, 'taskCount', count($query->tasks));

        return $query;
    }

    public function getKaryawan()
    {
        return $this->select('nama_lengkap', 'id')->get();
    }

    private function getEmployeesTaskCount(int $id) : int
    {
        return TaskModel::where('id_karyawan', $id)->get()->count();
    }
}

