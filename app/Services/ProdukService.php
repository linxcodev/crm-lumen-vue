<?php

namespace App\Services;

use App\Actions\SendResponse;
use App\Models\ProdukModel;

class ProdukService
{
    private ProdukModel $productsModel;

    public function __construct()
    {
        $this->productsModel = new ProdukModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->productsModel->storeProduct($requestedData, $adminId);
    }

    public function update(int $productId, array $requestedData, int $adminId)
    {
        return $this->productsModel->updateProduct($productId, $requestedData, $adminId);
    }

    public function loadProducts()
    {
        return $this->productsModel->getProducts();
    }

    public function loadPagination()
    {
        return $this->productsModel->getPaginate();
    }

    public function loadProduct(int $productId)
    {
        return $this->productsModel->getProduct($productId);
    }

    public function loadIsActiveFunction(int $productId, int $value)
    {
        return $this->productsModel->setActive($productId, $value);
    }

    public function loadProductsByCreatedAt()
    {
        return $this->productsModel->getProductsByCreatedAt();
    }

    public function checkIfProductHaveAssignedSale(int $productId)
    {
        $product = $this->productsModel->findClientByGivenClientId($productId);

        $countSales = $product->sales()->count();

        if ($countSales > 0) {
            return "Produk memiliki data penjualan!";
        } else {
            return;
        }
    }

    public function loadCountProducts()
    {
        return $this->productsModel->countProducts();
    }
}
