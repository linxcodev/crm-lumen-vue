<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeuanganRequest extends Controller
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
            'log' => 'required',
            'date' => 'required',
            'nama' => 'required|string',
            'type' => 'required|string',
            'gross' => 'required|string',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'id_perusahaan' => 'required|integer',
        ]);

        parent::__construct($request);
    }
}
