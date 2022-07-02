<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Services\KlienService;
use App\Services\SystemLogService;
use App\Http\Requests\KlienRequest;
use App\Http\Controllers\Controller;

class KlienController extends Controller
{
    private int $adminId;
    private KlienService $clientService;
    private SystemLogService $systemLogsService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->clientService = new KlienService();
        $this->systemLogsService = new SystemLogService();
    }

    public function show(int $id)
    {
        return SendResponse::acceptData([
            'clientDetails' => $this->clientService->loadClientDetails($id)
        ]);
    }

    public function index()
    {
        return SendResponse::acceptData([
            'clientsPaginate' => $this->clientService->loadPagination()
        ]);
    }

    public function store(KlienRequest $request)
    {
        $validatedData = $request->validated;
        $storedid = $this->clientService->execute($validatedData, $this->adminId);

        if ($storedid) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data klien telah ditambah dengan id: ' . $storedid, 
                $this->systemLogsService::successCode, 
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(KlienRequest $request, int $id)
    {
        $validatedData = $request->validated;
        if ($this->clientService->update($id, $validatedData, $this->adminId)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(int $id)
    {
        $clientAssigned = $this->clientService->checkIfClientHaveAssignedEmployeeOrCompany($id);

        if (!empty($clientAssigned)) {
            return SendResponse::acceptCustom([
                'error'     => true,
                'message'   => $clientAssigned
            ]);
        } else {
            $this->clientService->loadDeleteClient($id, $this->adminId);
        }

        return SendResponse::accept();
    }

    public function setIsActive(int $id, bool $value)
    {
        if ($this->clientService->loadSetActive($id, $value)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }
}
