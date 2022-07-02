<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Actions\SendResponse;
use App\Services\ProdukService;
use App\Services\SystemLogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukRequest;

class ProdukController extends Controller
{
    private int $adminId;
    private ProdukService $productsService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->productsService = new ProdukService();
        $this->systemLogsService = new SystemLogService();
    }

    public function index()
    {
        return SendResponse::acceptData($this->productsService->loadPagination());
    }

    public function show(int $id)
    {
        return SendResponse::acceptData($this->productsService->loadProduct($id));
    }

    public function store(ProdukRequest $request)
    {
        $validatedData = $request->validated;
        $storedProductId = $this->productsService->execute($validatedData, $this->adminId);

        if ($storedProductId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data produk telah ditambah dengan id: ' . $storedProductId,
                $this->systemLogsService::successCode,
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(ProdukRequest $request, int $id)
    {
        $validatedData = $request->validated;

        if ($this->productsService->update($id, $validatedData, $this->adminId)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, int $id)
    {
        $clientAssigned = $this->productsService->checkIfProductHaveAssignedSale($id);

        if (!empty($clientAssigned)) {
            return SendResponse::acceptCustom([
                'error'     => true,
                'message'   => $clientAssigned
            ]);
        }

        $productsDetails = $this->productsService->loadProduct($id);
        $productsDetails->delete();

        $this->systemLogsService->loadInsertSystemLogs(
            'Data produk telah dihapus dengan id: ' . $productsDetails->id,
            $this->systemLogsService::successCode,
            $this->adminId,
            $request->log
        );

        return SendResponse::accept();
    }

    public function setIsActive(Request $request, int $id, bool $value)
    {
        if ($this->productsService->loadIsActiveFunction($id, $value)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Status produk telah diubah dengan id: ' . $id,
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
