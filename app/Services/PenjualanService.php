<?php

namespace App\Services;

use App\Models\ProdukModel;
use App\Models\PenjualanModel;

class PenjualanService
{
    private PenjualanModel $salesModel;

    public function __construct()
    {
        $this->salesModel = new PenjualanModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->salesModel->storeSale($requestedData, $adminId);
    }

    public function update(int $saleId, array $requestedData, int $adminId)
    {
        return $this->salesModel->updateSale($saleId, $requestedData, $adminId);
    }

    public function loadSales()
    {
        return $this->salesModel->getSalesSortedByCreatedAt();
    }

    public function loadPaginate()
    {
        return $this->salesModel->getPaginate();
    }

    public function loadSale(int $saleId)
    {
        return $this->salesModel->getSale($saleId);
    }

    public function loadIsActiveFunction(int $saleId, int $value)
    {
        return $this->salesModel->setActive($saleId, $value);
    }

    public function loadProducts()
    {
        return ProdukModel::select('nama', 'id')->get();
    }

    public function loadCountSales()
    {
        return $this->salesModel->countSales();
    }
}
