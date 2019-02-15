<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/document', function () {
    return view('/vendor/l5-swagger/index', ['urlToDocs' => '/apidoc']);
});

Route::get('/apidoc', function () {
    return response(file_get_contents(storage_path('api-docs').'/api-docs.yaml'));
});
