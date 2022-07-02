<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Services\DealService;
use App\Services\TaskService;
use App\Services\KlienService;
use App\Services\ProdukService;
use App\Services\SettingService;
use App\Services\KaryawanService;
use App\Services\KeuanganService;
use App\Services\PenjualanService;
use App\Services\SystemLogService;
use App\Services\PerusahaanService;
use App\Services\HelpersFncService;
use App\Services\CalculateCashService;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    private KlienService $clientService;
    private HelpersFncService $helpersFncService;
    private PerusahaanService $companiesService;
    private ProdukService $productsService;
    private CalculateCashService $calculateCashService;
    private KaryawanService $employeesService;
    private DealService $dealsService;
    private KeuanganService $financesService;
    private TaskService $tasksService;
    private PenjualanService $salesService;
    private SystemLogService $systemLogService;
    private SettingService $settingsService;

    private int $cacheTime = 99;

    public function __construct()
    {
        $this->clientService = new KlienService();
        $this->helpersFncService = new helpersFncService();
        $this->companiesService = new PerusahaanService();
        $this->productsService = new ProdukService();
        $this->calculateCashService = new CalculateCashService();
        $this->employeesService = new KaryawanService();
        $this->dealsService = new DealService();
        $this->financesService = new KeuanganService();
        $this->tasksService = new TaskService();
        $this->salesService = new PenjualanService();
        $this->systemLogService = new SystemLogService();
        $this->settingsService = new SettingService();
    }

    public function index()
    {
        return SendResponse::acceptData([
            'dataWithAllTasks' => $this->helpersFncService->formatTasks(),
            'dataWithAllProducts' => $this->productsService->loadProductsByCreatedAt(),
            'dataWithAllCompanies' => $this->companiesService->loadCompaniesByCreatedAt(),
            'tugasKomplit' => $this->calculateCashService->loadTaskEveryMonth($isCompleted = true),
            'tugasDitambah' => $this->calculateCashService->loadTaskEveryMonth($isCompleted = false),
            
            'countTugas' => $this->tasksService->loadCountTasks(),
            'countProduk' => $this->productsService->loadCountProducts(),
            'countKaryawan' => $this->employeesService->loadCountEmployees(),
            'countPerusahaan' => $this->companiesService->loadCountCompanies(),
            'countPenjualan' => $this->salesService->loadCountSales(),
            'countKeuangan' => $this->financesService->loadCountFinances(),

            'countKlien' => $this->clientService->loadCountClients(),
            'deactivatedKlien' => $this->clientService->loadDeactivatedClients(),
            'klienInLatestMonth' => $this->clientService->loadClientsInLatestMonth(),

            'deactivatedPerusahaan' => $this->companiesService->loadDeactivatedCompanies(),
            'perusahaanInLatestMonth' => $this->companiesService->loadCompaniesInLatestMonth(),

            'deactivatedKaryawan' => $this->employeesService->loadDeactivatedEmployees(),
            'karyawanInLatestMonth' => $this->employeesService->loadEmployeesInLatestMonth(),

            'countDeal' => $this->dealsService->loadCountDeals(),
            'deactivatedDeal' => $this->dealsService->loadDeactivatedDeals(),
            'dealInLatestMonth' => $this->dealsService->loadDealsInLatestMonth(),

            'completedTugas' => $this->tasksService->loadCompletedTasks(),
            'uncompletedTugas' => $this->tasksService->loadUncompletedTasks(),
        ]);
    }

    //cache set for 99 minutes
    private function storeInCacheUsableVariables()
    {
        Cache::put('countClients', $this->clientService->loadCountClients(), $this->cacheTime);
        Cache::put('deactivatedClients', $this->clientService->loadDeactivatedClients(), $this->cacheTime);
        Cache::put('clientsInLatestMonth', $this->clientService->loadClientsInLatestMonth(), $this->cacheTime);
        Cache::put('countCompanies', $this->companiesService->loadCountCompanies(), $this->cacheTime);
        Cache::put('countEmployees', $this->employeesService->loadCountEmployees(), $this->cacheTime);
        Cache::put('countDeals', $this->dealsService->loadCountDeals(), $this->cacheTime);
        Cache::put('countFinances', $this->financesService->loadCountFinances(), $this->cacheTime);
        Cache::put('countProducts', $this->productsService->loadCountProducts(), $this->cacheTime);
        Cache::put('countTasks', $this->tasksService->loadCountTasks(), $this->cacheTime);
        Cache::put('countSales', $this->salesService->loadCountSales(), $this->cacheTime);
        Cache::put('deactivatedCompanies', $this->companiesService->loadDeactivatedCompanies(), $this->cacheTime);
        Cache::put('todayIncome', $this->calculateCashService->loadCountTodayIncome(), $this->cacheTime);
        Cache::put('yesterdayIncome', $this->calculateCashService->loadCountYesterdayIncome(), $this->cacheTime);
        Cache::put('cashTurnover', $this->calculateCashService->loadCountCashTurnover(), $this->cacheTime);
        Cache::put('countAllRowsInDb', $this->calculateCashService->loadCountAllRowsInDb(), $this->cacheTime);
        Cache::put('countSystemLogs', $this->systemLogService->loadCountLogs(), $this->cacheTime);
        Cache::put('companiesInLatestMonth', $this->companiesService->loadCompaniesInLatestMonth(), $this->cacheTime);
        Cache::put('employeesInLatestMonth', $this->employeesService->loadEmployeesInLatestMonth(), $this->cacheTime);
        Cache::put('deactivatedEmployees', $this->employeesService->loadDeactivatedEmployees(), $this->cacheTime);
        Cache::put('deactivatedDeals', $this->dealsService->loadDeactivatedDeals(), $this->cacheTime);
        Cache::put('dealsInLatestMonth', $this->dealsService->loadDealsInLatestMonth(), $this->cacheTime);
        Cache::put('completedTasks', $this->tasksService->loadCompletedTasks(), $this->cacheTime);
        Cache::put('uncompletedTasks', $this->tasksService->loadUncompletedTasks(), $this->cacheTime);
    }
}
