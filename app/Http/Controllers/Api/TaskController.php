<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Services\SystemLogService;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    private int $adminId;
    private TaskService $tasksService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->tasksService = new TaskService();
        $this->systemLogsService = new SystemLogService();
    }

    public function index()
    {
        return SendResponse::acceptData($this->tasksService->loadPaginate());
    }

    public function getKaryawan()
    {
        return SendResponse::acceptData($this->tasksService->pluckEmployees());
    }

    public function show(int $id)
    {
        return SendResponse::acceptData($this->tasksService->loadTask($id));
    }

    public function store(TaskRequest $request)
    {
        $validatedData = $request->validated;
        $storedTaskId = $this->tasksService->execute($validatedData, $this->adminId);

        if ($storedTaskId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data tugas telah ditambah dengan id: ' . $storedTaskId,
                $this->systemLogsService::successCode,
                $this->adminId,
                $validatedData['log']
            );
            
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(TaskRequest $request, int $id)
    {
        $validatedData = $request->validated;

        if ($this->tasksService->update($id, $validatedData, $this->adminId)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, int $id)
    {
        $dataOfTasks = $this->tasksService->loadTask($id);

        if ($dataOfTasks->completed == 0) {
            return SendResponse::acceptCustom([
                'error'     => true,
                'message'   => "Task belum komplit"
            ]);
        } else {
            $dataOfTasks->delete();

            $this->systemLogsService->loadInsertSystemLogs(
                'Data tugas telah dihapus dengan id: ' . $dataOfTasks->id,
                $this->systemLogsService::successCode,
                $this->adminId,
                $request->log
            );
        }

        return SendResponse::accept();
    }

    public function setIsActive(Request $request, int $id, bool $value)
    {
        if ($this->tasksService->loadIsActiveFunction($id, $value)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Status tugas telah diubah dengan id: ' . $id,
                $this->systemLogsService::successCode,
                $this->adminId,
                $request->log
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function completed(Request $request, int $id)
    {
        if ($this->tasksService->loadIsCompletedFunction($id, TRUE)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Tugas telah selesai dengan id: ' . $id,
                $this->systemLogsService::successCode,
                $this->adminId,
                $request->log
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function unCompleted(Request $request, int $id)
    {
        if ($this->tasksService->loadIsCompletedFunction($id, FALSE)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Tugas tidak selesai dengan id: ' . $id,
                $this->systemLogsService::successCode,
                $this->adminId,
                $request->log
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }
}
