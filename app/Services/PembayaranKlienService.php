<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade as PDF;
use App\Models\PembayaranKlienModel;

class PembayaranKlienService
{
    private PembayaranKlienModel $pembayaranKlienModel;

    public function __construct()
    {
        $this->pembayaranKlienModel = new PembayaranKlienModel();
    }

    public function loadPaginate()
    {
        return $this->pembayaranKlienModel->getPaginate();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->pembayaranKlienModel->store($requestedData, $adminId);
    }

    public function update(int $id, array $requestedData)
    {
        return $this->pembayaranKlienModel->updatePembayaran($id, $requestedData);
    }

    public function loadPembayaran(int $financeId)
    {
        return PembayaranKlienModel::find($financeId);
    }

    public function genInvoicePDF(int $id)
    {
        $data = [
            'data' => PembayaranKlienModel::with('klien', 'kategoriPembayaran')->find($id)
        ];

        $pdf = PDF::loadView('pdf.invoice-pdf', $data);

        return $pdf->download('invoice' . '.pdf');
    }
}
