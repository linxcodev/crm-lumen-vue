<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Services\PenjualanService;
use App\Services\SystemLogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PenjualanRequest;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    private int $adminId;
    private PenjualanService $salesService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->salesService = new PenjualanService();
        $this->systemLogsService = new SystemLogService();
    }

    public function index()
    {
        return SendResponse::acceptData($this->salesService->loadPaginate());
    }

    public function getProduk()
    {
        return SendResponse::acceptData($this->salesService->loadProducts());
    }

    public function show($id)
    {
        return SendResponse::acceptData($this->salesService->loadSale($id));
    }

    public function store(PenjualanRequest $request)
    {
        $validatedData = $request->validated;
        $storedSaleId = $this->salesService->execute($validatedData, $this->adminId);

        if ($storedSaleId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data penjualan telah ditambah dengan id: ' . $storedSaleId,
                $this->systemLogsService::successCode,
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(PenjualanRequest $request, int $id)
    {
        $validatedData = $request->validated;

        if ($this->salesService->update($id, $validatedData, $this->adminId)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, int $id)
    {
        $salesDetails = $this->salesService->loadSale($id);
        $salesDetails->delete();

        $this->systemLogsService->loadInsertSystemLogs(
            'Data penjualan telah dihapus dengan id: ' . $salesDetails->id, 
            $this->systemLogsService::successCode, 
            $this->getAdminId(),
            $request->log
        );

        return SendResponse::accept();
    }

    public function setIsActive(Request $request, int $id, bool $value)
    {
        if ($this->salesService->loadIsActiveFunction($id, $value)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Status penjualan telah diubah dengan id: ' . $id, 
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
