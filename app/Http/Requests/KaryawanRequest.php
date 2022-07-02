<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KaryawanRequest extends Controller
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
            'email' => 'required|email',
            'pekerjaan' => 'required|string',
            'catatan' => 'required|string',
            'id_klien' => 'required|integer',
            'log' => 'required'
        ]);

        parent::__construct($request);
    }
}
