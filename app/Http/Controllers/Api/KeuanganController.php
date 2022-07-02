<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use Illuminate\Http\Request;
use App\Services\KeuanganService;
use App\Services\SystemLogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\KeuanganRequest;

class KeuanganController extends Controller
{
    private int $adminId;
    private KeuanganService $financesService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->financesService = new KeuanganService();
        $this->systemLogsService = new SystemLogService();
    }

    public function show($id)
    {
        return SendResponse::acceptData($this->financesService->loadFinance($id));
    }

    public function index()
    {
        return SendResponse::acceptData($this->financesService->loadPagination());
    }

    public function store(KeuanganRequest $request)
    {
        $validatedData = $request->validated;
        $storedFinanceId = $this->financesService->execute($validatedData, $this->adminId);

        if ($storedFinanceId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data keuangan telah ditambah dengan id: ' . $storedFinanceId,
                $this->systemLogsService::successCode,
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(KeuanganRequest $request, $id)
    {
        $validatedData = $request->validated;

        if ($this->financesService->update($id, $validatedData)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, $id)
    {
        $dataOfFinances = $this->financesService->loadFinance($id);

        $dataOfFinances->delete();

        $this->systemLogsService->loadInsertSystemLogs(
            'Data keuangan telah dihapus dengan id: ' . $dataOfFinances->id,
            $this->systemLogsService::successCode,
            $this->adminId,
            $request->log
        );

        return SendResponse::accept();
    }

    public function setIsActive(Request $request, $id, $value)
    {
        if ($this->financesService->loadIsActiveFunction($id, $value)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Status keuangan telah diubah dengan id: ' . $id,
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
