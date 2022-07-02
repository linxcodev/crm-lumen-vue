<?php

namespace App\Services;

use App\Models\KategoriModel;

class KategoriService
{
    private KategoriModel $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    // public function getKategori()
    // {
    //     return $this->kategoriModel->getPaginate();
    // }
}
