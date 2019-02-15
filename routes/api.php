<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', 'Api\AuthController@register');

Route::post('/login', 'Api\AuthController@login');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/users', function (Request $request) {
        return $request->user();
    });

    Route::put('/users', 'Api\AuthController@update');

    Route::get('/roles', 'Api\RoleController@index');

    Route::get('/roles/{id}', 'Api\RoleController@showRoles');

    Route::get('/roles/{id}/{role}', 'Api\RoleController@show');

    Route::post('/roles', 'Api\RoleController@store');

    Route::delete('/roles/{id}/{role}', 'Api\RoleController@destroy');

    Route::get('/cars', 'Api\CarController@index')->middleware('scopes:list-car');

    Route::get('/cars/{id}', 'Api\CarController@show')->middleware('scopes:list-car');

    Route::post('/cars', 'Api\CarController@store')->middleware('scopes:create-car');

    Route::put('/cars/{car}', 'Api\CarController@update')->middleware('scopes:update-car');

    Route::delete('/cars/{car}', 'Api\CarController@destroy')->middleware('scopes:delete-car');
});
