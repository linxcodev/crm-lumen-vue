<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SystemLogModel extends Model
{
    protected $table = 'system_log';

    public function insertSystemLog($actions, int $statusCode, int $adminId = 1, $log)
    {
        self::insert(
            [
                'id_user' => $adminId,
                'aksi' => $actions,
                'status_code' => $statusCode,
                'tanggal' => Carbon::now(),
                'ip_address' => $log['ip_address'],
                'kota' => $log['kota'],
                'negara' => $log['negara'],
                'asn' => $log['asn'],
                'id_admin' => $adminId
            ]
        );

        return true;
    }

    public function countRows()
    {
        return self::all()->count();
    }
}
