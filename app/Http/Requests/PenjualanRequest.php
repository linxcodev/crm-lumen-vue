<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PenjualanRequest extends Controller
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
            'nama' => 'required|string',
            'quantity' => 'required|integer',
            'id_produk' => 'required|integer',
            'tangal_pembayaran' => 'required',
            'harga' => 'required',
            'log' => 'required',
        ]);

        parent::__construct($request);
    }
}
