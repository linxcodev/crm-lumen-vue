<?php

namespace App\Services;

use App\Models\KlienModel;
use App\Actions\SendResponse;

class KlienService
{
    private KlienModel $clientsModel;

    public function __construct()
    {
        $this->clientsModel = new KlienModel();
    }

    public function execute(array $requestedData, int $adminId): int
    {
        return $this->clientsModel->storeClient($requestedData, $adminId);
    }

    public function update(int $clientId, array $requestedData, int $adminId): int
    {
        return $this->clientsModel->updateClient($clientId, $requestedData, $adminId);
    }

    public function checkIfClientHaveAssignedEmployeeOrCompany(int $clientId)
    {
        $client = $this->clientsModel->getClientByGivenClientId($clientId);

        $countCompanies = $client->companies()->count();
        $countEmployees = $client->employees()->count();

        if ($countCompanies > 0) {
            return "Tidak dapat menghapus klien ini, telah menetapkan perusahaan!";
        }
        if ($countEmployees > 0) {
            return "Tidak dapat menghapus klien ini, telah menetapkan karyawan!";
        }

        return;
    }

    public function loadDeleteClient(int $clientId, int $adminId): ?bool
    {
        return $this->clientsModel->deleteClient($clientId, $adminId);
    }

    public function loadSetActive(int $clientId, bool $value)
    {
        $clientDetails = $this->clientsModel->getClientByGivenClientId($clientId);

        if ($this->clientsModel->setClientActive($clientDetails->id, $value)) {
            return SendResponse::accept();
        } else {
            return SendResponse::accept();
        }
    }

    public function loadClients()
    {
        return $this->clientsModel->getClientSortedBy();
    }

    public function loadPagination()
    {
        return $this->clientsModel->getPaginate();
    }

    public function loadClientDetails(int $clientId)
    {
        return $this->clientsModel->getClientByGivenClientId($clientId);
    }

    public function loadCountClients()
    {
        return $this->clientsModel->countClients();
    }

    public function loadDeactivatedClients()
    {
        return $this->clientsModel->getDeactivated();
    }

    public function loadClientsInLatestMonth()
    {
        return $this->clientsModel->getClientsInLatestMonth() . '%' ? : '0.00%';
    }
}
