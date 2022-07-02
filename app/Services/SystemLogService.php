<?php

namespace App\Services;

use App\Models\SystemLogModel;

class SystemLogService
{
    const successCode = 201;

    private SystemLogModel $systemLogModel;

    public function __construct()
    {
        $this->systemLogModel = new SystemLogModel();
    }

    public function loadInsertSystemLogs(string $actions, int $statusCode, int $adminId, $log)
    {
        return $this->systemLogModel->insertSystemLog($actions, $statusCode, $adminId, $log);
    }

    public function loadCountLogs()
    {
        return $this->systemLogModel->countRows();
    }
}
