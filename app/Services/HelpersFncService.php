<?php

namespace App\Services;

use App\Models\SettingModel;
use App\Models\SystemLogModel;
use App\Models\TaskModel;

class HelpersFncService
{
    /**
     * @return array
     */
    public static function getPrioritySize()
    {
        $arrayFromIteration = [];
        $sizeFromConfig = SettingModel::getSettingValue('priority_size');

        for ($i = 1; $i <= $sizeFromConfig; $i++) {
            $arrayFromIteration[] = $i;
        }
        return $arrayFromIteration;
    }

    /**
     * @return array
     */
    public function formatTasks()
    {
        $arrayWithFormattedTasks = [];
        $tasks = TaskModel::all()->sortBy('created_at', 0, true)->slice(0, 5);

        foreach ($tasks as $key => $task) {
            $nameTask = substr($task->nama, 0, 70);
            $nameTask .= '[..]';

            $arrayWithFormattedTasks[$key] = [
                'id' => $task->id,
                'nama' => $nameTask,
                'durasi' => $task->durasi,
                'created_at' => $task->created_at->diffForHumans()
            ];
        }

        return $arrayWithFormattedTasks;
    }

    /**
     * @return array
     */
    public function formatAllSystemLogs()
    {
        $formatLogs = [];
        $allLogs = SystemLogModel::orderBy('tanggal', 'desc')->get();

        foreach ($allLogs as $key => $result)
        {
            $formatLogs[$key] = [
                'id' => $result->id ?? '(not set)',
                'id_user' => $result->id_user ?? '(not set)',
                'aksi' => $result->aksi ?? '(not set)',
                'kota' => $result->kota ?? '(not set)',
                'negara' => $result->negara ?? '(not set)',
                'ip_address' => $result->ip_address ?? '(not set)',
                'tanggal' => $result->tanggal ?? '(not set)'
            ];
        }

        return $formatLogs;
    }
}
