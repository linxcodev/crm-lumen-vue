<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private AdminService $adminService;

    public function __construct()
    {
        $this->adminService = new AdminService();
    }

    public function changePassword(Request $request)
    {
        if($request->get('old_password') == null || $request->get('new_password') == null || $request->get('confirm_password') == null) {
            return SendResponse::badRequest("Semua Kolom harus diisi!");
        }

        if($this->adminService->loadValidatePassword($request->get('old_password'), $request->get('new_password'), $request->get('confirm_password'), $this->getAdminId())) {
            return SendResponse::accept();

        } else {
            return SendResponse::badRequest("Kamu menulis password yang salah!");
        }
    }
}
