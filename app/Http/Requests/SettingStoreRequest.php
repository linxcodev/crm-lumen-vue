<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingStoreRequest extends Controller
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
            'pagination_size' => 'required|integer',
            'currency' => 'required|string',
            'priority_size' => 'required|integer',
            'invoice_tax' => 'required|integer',
            'loading_circle' => 'required',
            'log' => 'required',
        ]);

        parent::__construct($request);
    }   
}
