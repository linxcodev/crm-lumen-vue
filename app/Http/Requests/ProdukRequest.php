<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdukRequest extends Controller
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
            'kategori' => 'required|string',
            'stok' => 'required|integer',
            'harga' => 'required|integer',
            'log' => 'required'
        ]);

        parent::__construct($request);
    }
}
