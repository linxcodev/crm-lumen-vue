<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KlienRequest extends Controller
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
            'nama_lengkap' => 'required|string',
            'phone' => 'required|string',
            'budget' => 'required|integer',
            'bagian' => 'required|string',
            'email' => 'required|email',
            'lokasi' => 'required|string',
            'zip' => 'required|string',
            'kota' => 'required|string',
            'negara' => 'required|string',
            'log' => 'required',
        ]);

        parent::__construct($request);
    }
}
