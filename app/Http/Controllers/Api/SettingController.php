<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Services\SettingService;
use App\Services\SystemLogService;
use App\Services\HelpersFncService;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingStoreRequest;

class SettingController extends Controller
{
    private SettingService $settingsService;
    private SystemLogService $systemLogsService;
    private HelpersFncService $helpersFncService;

    public function __construct()
    {
        $this->settingsService = new SettingService();
        $this->systemLogsService = new SystemLogService();
        $this->helpersFncService = new HelpersFncService();
    }

    public function index()
    {
        return SendResponse::acceptData([
            'pengaturan' => $this->settingsService->loadAllSettings(),
            'list_log' => $this->helpersFncService->formatAllSystemLogs()
        ]);
    }

    public function update(SettingStoreRequest $request)
    {
        $validatedData = $request->validated;

        if ($this->settingsService->loadUpdateSettings($validatedData)) {
            $this->systemLogsService->loadInsertSystemLogs(
                'Pengaturan telah dirubah.',
                $this->systemLogsService::successCode, 
                $this->getAdminId(),
                $validatedData['log']
            );
            
            return SendResponse::accept();
        } else {
            return SendResponse::badRequest();
        }
    }
}
