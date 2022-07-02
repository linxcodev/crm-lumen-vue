<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Actions\SendResponse;
use App\Services\DealService;
use App\Http\Requests\DealRequest;
use App\Services\SystemLogService;
use App\Http\Controllers\Controller;

class DealController extends Controller
{
    private int $adminId;
    private DealService $dealsService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->dealsService = new DealService();
        $this->systemLogsService = new SystemLogService();
    }

    public function index()
    {
        return SendResponse::acceptData($this->dealsService->loadPaginate());
    }

    public function getPerusahaan()
    {
        return SendResponse::acceptData($this->dealsService->pluckPerusahaan());
    }

    public function show(int $id)
    {
        return SendResponse::acceptData([
            'deal' => $this->dealsService->loadDeal($id), 
            'dealsTerms' => $this->dealsService->loadDealsTerms($id)
        ]);
    }

    public function store(DealRequest $request)
    {
        $validatedData = $request->validated;
        $storedDealId = $this->dealsService->execute($validatedData, $this->adminId);

        if ($storedDealId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data deal telah ditambah dengan id: ' . $storedDealId, 
                $this->systemLogsService::successCode, 
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(DealRequest $request, int $id)
    {
        $validatedData = $request->validated;

        if ($this->dealsService->update($id, $validatedData, $this->adminId)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, int $id)
    {
        $countDealTerms = $this->dealsService->countDealTerms($id);

        if ($countDealTerms > 0) {
            return SendResponse::acceptCustom([
                'error'     => true,
                'message'   => "Memiliki deal term."
            ]);
        }

        $dataOfDeals = $this->dealsService->loadDeal($id);
        $dataOfDeals->delete();

        $this->systemLogsService->loadInsertSystemLogs(
            'Data deal telah didelete dengan id: ' . $dataOfDeals->id, 
            $this->systemLogsService::successCode, 
            $this->adminId,
            $request->log
        );

        return SendResponse::accept();
    }

    public function setIsActive(Request $request, int $id, bool $value)
    {
        if ($this->dealsService->loadSetActive($id, $value, $this->adminId)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Status deal telah diubah dengan id: ' . $id, 
                $this->systemLogsService::successCode, 
                $this->adminId,
                $request->log
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function storeDealTerms(Request $request)
    {
        $validatedData = $request->all();

        if ($this->dealsService->loadStoreDealTerms($validatedData)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data deal term telah ditambah dengan id: ' . $validatedData['id_deal'], 
                $this->systemLogsService::successCode, 
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function generateDealTermsInPDF()
    {
        $termId = request()->id_term;
        $id = request()->id_deal;

        return $this->dealsService->loadGenerateDealTermsInPDF($termId, $id);
    }

    public function deleteDealTerm(Request $request)
    {
        $termId = $request->get('id_term');

        $this->dealsService->loadDeleteTerm($termId);

        $this->systemLogsService->loadInsertSystemLogs(
            'Data deal term telah didelete dengan id: ' . $termId, 
            $this->systemLogsService::successCode, 
            $this->adminId,
            $request->log
        );

        return SendResponse::accept();
    }
}
