<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskModel extends Model
{
    use SoftDeletes;

    protected $table = 'task';
    protected $dates = ['deleted_at'];

    public function employees()
    {
        return $this->belongsTo(KaryawanModel::class, 'id_karyawan');
    }

    public function storeTask(array $requestedData, int $adminId)
    {
        return $this->insertGetId(
            [
                'is_active'   => true,
                'dibuat_oleh' => $adminId,
                'created_at'  => date('Y-m-d'),
                'nama'        => $requestedData['nama'],
                'durasi'      => $requestedData['durasi'],
                'id_karyawan' => $requestedData['id_karyawan'],
            ]
        );
    }

    public function updateTask(int $taskId, array $requestedData, int $adminId) : int
    {
        return $this->where('id', '=', $taskId)->update(
            [
                'diedit_oleh' => $adminId,
                'updated_at'  => date('Y-m-d'),
                'nama'        => $requestedData['nama'],
                'durasi'      => $requestedData['durasi'],
                'id_karyawan' => $requestedData['id_karyawan'],
            ]
        );
    }

    public function setActive(int $taskId, int $activeType) : int
    {
        return $this->where('id', '=', $taskId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => date('Y-m-d')
            ]
        );
    }

    public function setCompleted(int $taskId, int $completeType) : int
    {
        return $this->where('id', '=', $taskId)->update(
            [
                'completed' => $completeType,
                'updated_at' => date('Y-m-d')
            ]
        );
    }

    public function countTasks()
    {
        return $this->all()->count();
    }

    public function getAllCompletedTasks()
    {
        $tasks = $this->where('completed', '=', 1)->count();

        $taskAll = $this->all()->count();

        $percentage = round(($tasks / $taskAll) * 100);

        return $tasks . ' (' . $percentage .  '%)';
    }

    public function getTask(int $taskId)
    {
        return $this->with('employees')->find($taskId);
    }

    public function getTasks()
    {
        return $this->all()->sortBy('created_at');
    }

    public function getPaginate()
    {
        $query = $this->with('employees');

        if (request()->q != '') {
            $query = $query->where('nama', 'LIKE', '%' . request()->q . '%');
        }

        $query = $query->paginate(SettingModel::getSettingValue('pagination_size'));
        
        return $query;
    }

    public function getAllUncompletedTasks()
    {
        return [
            'tasks' => $this->where('completed', '=', 0)->count(),
            'all' => $this->all()->count()
        ];
    }
}
