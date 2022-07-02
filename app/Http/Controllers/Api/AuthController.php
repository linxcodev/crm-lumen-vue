<?php

namespace App\Http\Controllers\Api;

use App\Models\AdminModel;
use App\Actions\SendResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show resource.
     *
     * @return void
     */
    public function login()
    {
        $admin = AdminModel::where('email', request()->email)->first();
        
        if (is_null($admin) || !Hash::check(request()->password, $admin->password)) {
            return SendResponse::badRequest('incorrect email/password');
        }

        $token = JWTAuth::fromUser($admin);
        return SendResponse::respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticated()
    {
        return SendResponse::acceptData(auth()->user());
    }
}
