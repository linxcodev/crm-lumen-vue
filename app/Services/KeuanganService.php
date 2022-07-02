<?php

namespace App\Services;

use App\Models\SettingModel;
use App\Models\KeuanganModel;

class KeuanganService
{
    private KeuanganModel $financesModel;

    public function __construct()
    {
        $this->financesModel = new KeuanganModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->financesModel->storeFinance($requestedData, $adminId);
    }

    public function update(int $financeId, array $requestedData)
    {
        return $this->financesModel->updateFinance($financeId, $requestedData);
    }

    public function loadCalculateNetAndVatByGivenGross($gross)
    {
        $invoice_tax = SettingModel::getSettingValue('invoice_tax') ?: 3;

        $getTaxValueFromConfig = $invoice_tax / 100;
        $getGrossValueFromInput = $gross;

        $vat = $getGrossValueFromInput * $getTaxValueFromConfig;

        $net = $getGrossValueFromInput - $vat;

        return [
            'net' => $net,
            'vat' => $vat,
        ];
    }

    public function loadFinances()
    {
        return $this->financesModel->getFinancesSortedByCreatedAt();
    }

    public function loadPagination()
    {
        return $this->financesModel->getPaginate();
    }

    public function loadFinance(int $financeId)
    {
        return $this->financesModel::with('companies')->find($financeId);
    }

    public function loadIsActiveFunction(int $financeId, int $value)
    {
        return $this->financesModel->setActive($financeId, $value);
    }

    public function pluckCompanies()
    {
        return $this->financesModel->getPluckCompanies();
    }

    public function loadCountFinances()
    {
        return $this->financesModel->countFinances();
    }
}
