<?php

namespace App\Services;

use App\Models\KlienModel;
use App\Models\SettingModel;
use App\Models\KaryawanModel;

class KaryawanService
{
    private KlienModel $clientsModel;
    private KaryawanModel $employeesModel;

    public function __construct()
    {
        $this->clientsModel = new KlienModel();
        $this->employeesModel = new KaryawanModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->employeesModel->storeEmployee($requestedData, $adminId);
    }

    public function update(int $employeeId, array $requestedData, int $adminId)
    {
        return $this->employeesModel->updateEmployee($employeeId, $requestedData, $adminId);
    }

    public function loadEmployees()
    {
        return $this->employeesModel->getEmployees();
    }

    public function loadPaginate()
    {
        return $this->employeesModel->getPaginate();
    }

    public function pluckKlien()
    {
        return $this->clientsModel->getKlien();
    }

    public function loadEmployeeDetails(int $employeeId)
    {
        return $this->employeesModel->getEmployeeDetails($employeeId);
    }

    public function countEmployeeTasks(KaryawanModel $dataOfEmployees)
    {
        return $dataOfEmployees->tasks()->get()->count();
    }

    public function loadIsActiveFunction(int $employeeId, int $value)
    {
        return $this->employeesModel->setActive($employeeId, $value);
    }

    public function loadCountEmployees()
    {
        return $this->employeesModel->countEmployees();
    }

    public function loadEmployeesInLatestMonth()
    {
        return $this->employeesModel->getEmployeesInLatestMonth() . '%' ? : '0.00%';
    }

    public function loadDeactivatedEmployees()
    {
        return $this->employeesModel->getDeactivated();
    }
}
