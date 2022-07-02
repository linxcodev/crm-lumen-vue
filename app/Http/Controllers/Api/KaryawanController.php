<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Services\KaryawanService;
use App\Services\SystemLogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\KaryawanRequest;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    private int $adminId;
    private KaryawanService $employeesService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->employeesService = new KaryawanService();
        $this->systemLogsService = new SystemLogService();
    }

    public function index()
    {
        return SendResponse::acceptData($this->employeesService->loadPaginate());
    }

    public function getKlien()
    {
        return SendResponse::acceptData($this->employeesService->pluckKlien());
    }

    public function show(int $id)
    {
        return SendResponse::acceptData($this->employeesService->loadEmployeeDetails($id));
    }

    public function store(KaryawanRequest $request)
    {
        $validatedData = $request->validated;
        $storedEmployeeId = $this->employeesService->execute($validatedData, $this->adminId);

        if ($storedEmployeeId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data karyawan telah ditambah dengan id: ' . $storedEmployeeId, 
                $this->systemLogsService::successCode, 
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(KaryawanRequest $request, int $id)
    {
        $validatedData = $request->validated;

        if ($this->employeesService->update($id, $validatedData, $this->adminId)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, int $id)
    {
        $dataOfEmployees = $this->employeesService->loadEmployeeDetails($id);
        $countTasks = $this->employeesService->countEmployeeTasks($dataOfEmployees);

        if ($countTasks > 0) {
            return SendResponse::badRequest();
        }

        $dataOfEmployees->delete();

        $this->systemLogsService->loadInsertSystemLogs(
            'Data karyawan telah dihapus dengan id: ' . $dataOfEmployees->id, 
            $this->systemLogsService::successCode, 
            $this->getAdminId(),
            $request->log
        );

        return SendResponse::accept();
    }

    public function setIsActive(Request $request, $id, $value)
    {
        if ($this->employeesService->loadIsActiveFunction($id, $value)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Status karyawan telah diubah dengan id: ' . $id, 
                $this->systemLogsService::successCode, 
                $this->getAdminId(),
                $request->log
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }
}

