<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Services\SystemLogService;
use App\Services\PerusahaanService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PerusahaanRequest;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    private int $adminId;
    private PerusahaanService $companiesService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->companiesService = new PerusahaanService();
        $this->systemLogsService = new SystemLogService();
    }

    public function index()
    {
        return SendResponse::acceptData($this->companiesService->loadPagination());
    }

    public function getKlien()
    {
        return SendResponse::acceptData($this->companiesService->pluckKlien());
    }

    public function show(int $id)
    {
        return SendResponse::acceptData($this->companiesService->loadCompany($id));
    }

    public function store(PerusahaanRequest $request)
    {
        $validatedData = $request->validated;
        $storedCompanyId = $this->companiesService->execute($validatedData, $this->adminId);

        if ($storedCompanyId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data perusahaan telah ditambah dengan id: ' . $storedCompanyId, 
                $this->systemLogsService::successCode, 
                $this->adminId,
                $validatedData['log']
            );
            
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(PerusahaanRequest $request, int $id)
    {
        $validatedData = $request->validated;

        if ($this->companiesService->update($id, $validatedData, $this->adminId)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, int $id)
    {
        $dataOfCompanies = $this->companiesService->loadCompany($id);
        $countDeals = $this->companiesService->loadCountAssignedDeals($id);

        if ($countDeals > 0) {
            return SendResponse::acceptCustom([
                'error'     => true,
                'message'   => "Memiliki data deal."
            ]);
        }

        $dataOfCompanies->delete();

        $this->systemLogsService->loadInsertSystemLogs(
            'Data perusahaan telah dihapus dengan id: ' . $dataOfCompanies->id, 
            $this->systemLogsService::successCode, 
            $this->getAdminId(),
            $request->log
        );

        return SendResponse::accept();
    }

    public function setIsActive(Request $request, int $id, bool $value)
    {
        if ($this->companiesService->loadSetActive($id, $value)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data perusahaan telah dirubah status dengan id: ' . $id, 
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
