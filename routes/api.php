<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api', 'namespace' => 'Api'], function () use ($router) {
    $router->post('login',  'AuthController@login');
    $router->post('password-change',  'AdminController@changePassword');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('authenticated',  'AuthController@authenticated');

        $router->get('dashboard',  'DashboardController@index');

        // system pengaturan
        $router->get('pengaturan',  'SettingController@index');
        $router->put('pengaturan',  'SettingController@update');

        $router->group(['prefix' => 'klien'], function () use ($router) {
            $router->get('/pembayaran',  'PembayaranKlienController@index');
            $router->get('/pembayaran/tipe',  'PembayaranKlienController@getTipePembayaran');
            $router->get('/pembayaran/{id}',  'PembayaranKlienController@show');
            $router->get('/pembayaran/generate-pdf/{id}',  'PembayaranKlienController@genInvoicePDF');
            
            $router->post('/pembayaran',  'PembayaranKlienController@store');
            $router->post('/pembayaran/{id}',  'PembayaranKlienController@delete');
            $router->put('/pembayaran/{id}',  'PembayaranKlienController@update');

            $router->get('/',  'KlienController@index');
            $router->get('/{id}',  'KlienController@show');

            $router->post('/',  'KlienController@store');
            $router->post('/set-aktive/{id}/{value}',  'KlienController@setIsActive');
            
            $router->put('/{id}',  'KlienController@update');
            $router->delete('/{id}',  'KlienController@delete');
            
        });

        $router->group(['prefix' => 'perusahaan'], function () use ($router) {
            $router->get('/',  'PerusahaanController@index');
            $router->get('/get-klien',  'PerusahaanController@getKlien');
            $router->get('/{id}',  'PerusahaanController@show');

            $router->post('/',  'PerusahaanController@store');
            $router->post('/set-aktive/{id}/{value}',  'PerusahaanController@setIsActive');

            $router->put('/{id}',  'PerusahaanController@update');
            $router->post('/{id}',  'PerusahaanController@delete');
        });

        $router->group(['prefix' => 'deal'], function () use ($router) {
            $router->get('/',  'DealController@index');
            $router->get('/get-perusahaan',  'DealController@getPerusahaan');
            $router->get('/{id}',  'DealController@show');

            $router->post('/',  'DealController@store');
            $router->post('/set-aktive/{id}/{value}',  'DealController@setIsActive');
            
            $router->post('/term',  'DealController@storeDealTerms');
            $router->post('/term/delete',  'DealController@deleteDealTerm');

            $router->put('/{id}',  'DealController@update');
            $router->post('/{id}',  'DealController@delete');
            
            $router->get('/term/generate-pdf',  'DealController@generateDealTermsInPDF');
        });

        $router->group(['prefix' => 'karyawan'], function () use ($router) {
            $router->get('/',  'KaryawanController@index');
            $router->get('/get-klien',  'KaryawanController@getKlien');
            $router->get('/{id}',  'KaryawanController@show');

            $router->post('/',  'KaryawanController@store');
            $router->post('/set-aktive/{id}/{value}',  'KaryawanController@setIsActive');

            $router->put('/{id}',  'KaryawanController@update');
            $router->post('/{id}',  'KaryawanController@delete');
        });

        $router->group(['prefix' => 'task'], function () use ($router) {
            $router->get('/',  'TaskController@index');
            $router->get('/get-karyawan',  'TaskController@getKaryawan');
            $router->get('/{id}',  'TaskController@show');

            $router->post('/',  'TaskController@store');
            $router->post('/completed/{id}',  'TaskController@completed');
            $router->post('/uncompleted/{id}',  'TaskController@unCompleted');
            $router->post('/set-aktive/{id}/{value}',  'TaskController@setIsActive');

            $router->put('/{id}',  'TaskController@update');
            $router->post('/{id}',  'TaskController@delete');
        });

        $router->group(['prefix' => 'penjualan'], function () use ($router) {
            $router->get('/',  'PenjualanController@index');
            $router->get('/get-produk',  'PenjualanController@getProduk');
            $router->get('/{id}',  'PenjualanController@show');

            $router->post('/',  'PenjualanController@store');
            $router->post('/set-aktive/{id}/{value}',  'PenjualanController@setIsActive');

            $router->put('/{id}',  'PenjualanController@update');
            $router->post('/{id}',  'PenjualanController@delete');
        });

        $router->group(['prefix' => 'produk'], function () use ($router) {
            $router->get('/',  'ProdukController@index');
            $router->get('/{id}',  'ProdukController@show');

            $router->post('/',  'ProdukController@store');
            $router->post('/set-aktive/{id}/{value}',  'ProdukController@setIsActive');

            $router->put('/{id}',  'ProdukController@update');
            $router->post('/{id}',  'ProdukController@delete');
        });

        $router->group(['prefix' => 'keuangan'], function () use ($router) {
            $router->get('/',  'KeuanganController@index');
            $router->get('/{id}',  'KeuanganController@show');

            $router->post('/',  'KeuanganController@store');
            $router->post('/set-aktive/{id}/{value}',  'KeuanganController@setIsActive');

            $router->put('/{id}',  'KeuanganController@update');
            $router->post('/{id}',  'KeuanganController@delete');
        });
    });
});