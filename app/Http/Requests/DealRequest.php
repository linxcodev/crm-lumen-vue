<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealRequest extends Controller
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
            'nama' => 'required|string',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required',
            'id_perusahaan' => 'required|integer',
        ]);

        parent::__construct($request);
    }
}
