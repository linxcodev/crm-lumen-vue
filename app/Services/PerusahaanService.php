<?php

namespace App\Services;

use App\Models\DealModel;
use App\Models\KlienModel;
use App\Models\PerusahaanModel;

class PerusahaanService
{
    private KlienModel $clientsModel;
    private PerusahaanModel $companiesModel;

    public function __construct()
    {
        $this->clientsModel = new KlienModel();
        $this->companiesModel = new PerusahaanModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->companiesModel->storeCompany($requestedData, $adminId);
    }

    public function update(int $companiesId, array $requestedData, $adminId)
    {
        return $this->companiesModel->updateCompany($companiesId, $requestedData, $adminId);
    }

    public function loadCompanies()
    {
        return $this->companiesModel->getAll();
    }

    public function loadPagination()
    {
        return $this->companiesModel->getPaginate();
    }

    public function pluckKlien()
    {
        return $this->clientsModel->getKlien();
    }

    public function loadCompany(int $companyId)
    {
        return $this->companiesModel->getCompany($companyId);
    }

    public function loadCountAssignedDeals(int $companiesId)
    {
        return DealModel::where('id_perusahaan', $companiesId)->get()->count();
    }

    public function loadSetActive(int $companiesId, bool $value)
    {
        return $this->companiesModel->setActive($companiesId, $value);
    }

    public function loadCompaniesByCreatedAt()
    {
        return $this->companiesModel->getCompaniesSortedByCreatedAt();
    }

    public function loadCountCompanies()
    {
        return $this->companiesModel->countCompanies();
    }

    public function loadDeactivatedCompanies()
    {
        return $this->companiesModel->getDeactivated();
    }

    public function loadCompaniesInLatestMonth()
    {
        return $this->companiesModel->getCompaniesInLatestMonth() . '%' ? : '0.00%';
    }
}
