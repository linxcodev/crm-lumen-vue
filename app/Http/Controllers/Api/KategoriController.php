<?php

namespace App\Http\Controllers\Api;

use App\Actions\SendResponse;
use App\Services\KategoriService;
use App\Http\Controllers\Controller;

class KategoriController extends Controller
{
    private KategoriService $kategoriService;

    public function __construct()
    {
        $this->kategoriService = new KategoriService();
    }

    // public function index()
    // {
    //     return SendResponse::acceptData($this->kategoriService->getKategori());
    // }
}
