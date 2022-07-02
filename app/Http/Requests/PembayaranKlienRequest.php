<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembayaranKlienRequest extends Controller
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
            'catatan'         => 'string',
            'log'             => 'required',
            'nominal'         => 'required',
            'id_klien'        => 'required',
            'tipe_pembayaran' => 'required',
        ]);

        parent::__construct($request);
    }
}
