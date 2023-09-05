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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'book'], function () use ($router) {
        $router->get('/', 'BookController@index');
        $router->post('/simpan', 'BookController@create');
        $router->get('/cetak-excel', 'BookController@exportExcel');
        $router->get('/cetak-pdf', 'BookController@exportPdf');
        $router->get('/{id}/detail', 'BookController@show');
        $router->delete('/{id}/hapus', 'BookController@destroy');
    });
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', 'UserController@index');
        $router->get('/cetak-pdf', 'UserController@exportPdf');
    });
    $router->group(['prefix' => 'petugas'], function () use ($router) {
        $router->get('/', 'PetugasController@index');
        $router->get('/cetak-pdf', 'PetugasController@exportPdf');
    });
    $router->get('/merge-pdf', 'HomeController@cetakSemuaPdf');
    $router->get('/buat-zip', 'HomeController@createZip');

    $router->post('/run-engine', 'HomeController@runEngine');
});
