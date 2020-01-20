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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//localhost:8000/api/besmart/0/{id} ---> pauza
Route::get('/besmart/0/{id}', 'ApiControllerBreak@returnMessage');

//localhost:8000/api/besmart/1/{id} ---> dolazak/odlazak s posla
Route::get('/besmart/1/{id}', 'ApiControllerWork@returnMessage');

//localhost:8000/api/besmart/addUser/ ---> dodavanje novog usera
Route::get('/besmart/addUser/{keyId}/{name}/{lastName}/{gender}', 'ApiNewUserController@addNewUser');

//localhost:8000/api/besmart/addUser/ ---> dodavanje novog Ključića
Route::get('/besmart/addKey/{userKeyId}', 'ApiNewUserController@addKey');

//localhost:8000/api/besmart/autoclose/ ---> zatvaranje svih otvorenih pauza i dolazaka
Route::get('/besmart/autoclose/', 'AutoCloseController@close');

