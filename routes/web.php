<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Models\PembayaranKlienModel;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('invoice', function () use ($router) {
    $data = [
        'data' => PembayaranKlienModel::with('klien', 'tipePembayaran')->find(1)
    ];

    return view('pdf.invoice-pdf', $data);
});

$router->get('{route:.*}', function () use ($router) {
    return view('app');
});
