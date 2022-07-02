<?php

namespace App\Services;

use App\Models\KaryawanModel;
use App\Models\TaskModel;

class TaskService
{
    private TaskModel $tasksModel;
    private KaryawanModel $employeesModel;

    public function __construct()
    {
        $this->tasksModel = new TaskModel();
        $this->employeesModel = new KaryawanModel();
    }

    public function execute(array $validateData, int $adminId)
    {
        return $this->tasksModel->storeTask($validateData, $adminId);
    }

    public function update(int $taskId, array $validatedData, int $adminId)
    {
        return $this->tasksModel->updateTask($taskId, $validatedData, $adminId);
    }

    public function loadTasks()
    {
        return $this->tasksModel->getTasks();
    }

    public function loadPaginate()
    {
        return $this->tasksModel->getPaginate();
    }

    public function loadTask(int $taskId)
    {
        return $this->tasksModel->getTask($taskId);
    }

    public function loadIsActiveFunction(int $taskId, bool $value)
    {
        return $this->tasksModel->setActive($taskId, $value);
    }

    public function loadIsCompletedFunction(int $taskId, bool $value)
    {
        return $this->tasksModel->setCompleted($taskId, $value);
    }

    public function pluckEmployees()
    {
        return $this->employeesModel->getKaryawan();
    }

    public function loadCountTasks()
    {
        return $this->tasksModel->countTasks();
    }

    public function loadCompletedTasks()
    {
        return $this->tasksModel->getAllCompletedTasks();
    }

    public function loadUncompletedTasks()
    {
        $data = $this->tasksModel->getAllUncompletedTasks();

        $percentage = round(($data['tasks'] / $data['all']) * 100);

        return $data['tasks'] . ' (' . $percentage .  '%)';
    }
}
