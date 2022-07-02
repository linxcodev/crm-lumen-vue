<?php

namespace App\Services;

use App\Models\DealModel;
use App\Models\DealTermModel;
use App\Models\PerusahaanModel;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class DealService
{
    private DealModel $dealsModel;
    private DealTermModel $dealsTermsModel;
    private PerusahaanModel $companiesModel;

    public function __construct()
    {
        $this->dealsModel = new DealModel();
        $this->dealsTermsModel = new DealTermModel();
        $this->companiesModel = new PerusahaanModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->dealsModel->storeDeal($requestedData, $adminId);
    }

    public function update(int $dealId, array $requestedData, int $adminId)
    {
        return $this->dealsModel->updateDeal($dealId, $requestedData, $adminId);
    }

    public function loadPaginate()
    {
        return $this->dealsModel->getPaginate();
    }

    public function loadDeal(int $dealId)
    {
        return $this->dealsModel->getDeal($dealId);
    }

    public function pluckPerusahaan()
    {
        return $this->companiesModel->pluckData();
    }

    public function loadSetActive(int $dealId, bool $value, int $adminId)
    {
        return $this->dealsModel->setActive($dealId, $value, $adminId);
    }

    public function loadCountDeals()
    {
        return $this->dealsModel->countDeals();
    }

    public function loadDeactivatedDeals()
    {
        return $this->dealsModel->getDeactivated();
    }

    public function loadDealsInLatestMonth()
    {
        return $this->dealsModel->getDealsInLatestMonth() . '%' ? : '0.00%';
    }

    public function loadStoreDealTerms(array $validatedData)
    {
        return $this->dealsTermsModel->storeDealTerms($validatedData);
    }

    public function loadDealsTerms(int $dealId)
    {
        $query = $this->dealsTermsModel->getDealTerms($dealId);

        foreach($query as $key => $value) {
            $query[$key]['formattedDate'] = $this->generateDate($value['created_at']);
        }

        return $query;
    }

    private function generateDate($date)
    {
        return Carbon::parse($date)->format('d M, Y');
    }

    public function loadGenerateDealTermsInPDF(int $termId, int $dealId)
    {
        $data = [
            'body' => $this->dealsTermsModel->getTermsBody($termId)
        ];

        $dealName = $this->dealsModel->getName($dealId);

        $pdf = PDF::loadView('pdf.terms-pdf', $data);

        return $pdf->download($dealName . '.pdf');
    }

    public function loadDeleteTerm(int $termId)
    {
        return $this->dealsTermsModel->deleteTerm($termId);
    }

    public function countDealTerms(int $dealId)
    {
        return $this->dealsTermsModel->countAssignedDealTerms($dealId);
    }
}
