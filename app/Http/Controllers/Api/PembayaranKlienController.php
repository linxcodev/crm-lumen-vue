<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\KategoriModel;
use App\Actions\SendResponse;
use App\Services\SystemLogService;
use App\Http\Controllers\Controller;
use App\Services\PembayaranKlienService;
use App\Http\Requests\PembayaranKlienRequest;

class PembayaranKlienController extends Controller
{
    private int $adminId;
    private SystemLogService $systemLogsService;
    private PembayaranKlienService $pembayaranKlienService;

    public function __construct()
    {
        $this->adminId = $this->getAdminId();
        $this->systemLogsService = new SystemLogService();
        $this->pembayaranKlienService = new PembayaranKlienService();
    }

    public function index()
    {
        return SendResponse::acceptData($this->pembayaranKlienService->loadPaginate());
    }

    public function show($id)
    {
        return SendResponse::acceptData($this->pembayaranKlienService->loadPembayaran($id));
    }

    public function getTipePembayaran()
    {
        return SendResponse::acceptData(KategoriModel::getOption('tipe_pembayaran'));
    }

    public function store(PembayaranKlienRequest $request)
    {
        $validatedData = $request->validated;
        $storedId = $this->pembayaranKlienService->execute($validatedData, $this->adminId);

        if ($storedId) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Data pembayaran klien telah ditambah dengan id: ' . $storedId, 
                $this->systemLogsService::successCode, 
                $this->adminId,
                $validatedData['log']
            );

            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function update(PembayaranKlienRequest $request, $id)
    {
        $validatedData = $request->validated;

        if ($this->pembayaranKlienService->update($id, $validatedData)) {
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }

    public function delete(Request $request, $id)
    {
        $data = $this->pembayaranKlienService->loadPembayaran($id);

        $data->delete();

        $this->systemLogsService->loadInsertSystemLogs(
            'Data pembayaran klien telah dihapus dengan id: ' . $data->id,
            $this->systemLogsService::successCode,
            $this->adminId,
            $request->log
        );

        return SendResponse::accept();
    }

    public function genInvoicePDF(int $id)
    {
        return $this->pembayaranKlienService->genInvoicePDF($id);
    }
}
