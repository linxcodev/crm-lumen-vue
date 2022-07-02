<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerusahaanRequest extends Controller
{
    public $validated;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function __construct(Request $request)
    {
        $this->validated = $this->validate($request, [
            'log'               => 'required',
            'fax'               => 'required|string',
            'nama'              => 'required|string',
            'kota'              => 'required|string',
            'phone'             => 'required|string',
            'negara'            => 'required|string',
            'kode_pos'          => 'required|string',
            'id_klien'          => 'required|integer',
            'deskripsi'         => 'required|string',
            'nomor_pajak'       => 'required|integer',
            'jumlah_karyawan'   => 'required|integer',
            'alamat_penagihan'  => 'required|string',
        ]);

        parent::__construct($request);
    }
}
