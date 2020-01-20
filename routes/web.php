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

Route::get('/', 'ReportController@index');


//localhost:8000/besmart/0/{id} ---> pauza
Route::get('/besmart/0/{id}', 'ApiControllerBreak@returnMessage');

//localhost:8000/besmart/1/{id} ---> dolazak/odlazak s posla
Route::get('/besmart/1/{id}', 'ApiControllerWork@returnMessage');

//localhost:8000/besmart/addUser/ ---> dodavanje novog usera
Route::get('/besmart/addUser/{keyId}/{name}/{lastName}/{gender}', 'ApiNewUserController@addNewUser');

//localhost:8000/besmart/addKey/ ---> dodavanje novog Ključića
Route::get('/besmart/addKey/{userKeyId}', 'ApiNewUserController@addKey');

//localhost:8000/besmart/autoclose/ ---> zatvaranje svih otvorenih pauza i dolazaka
Route::get('/besmart/autoclose/', 'AutoCloseController@close');


Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'ReportController@index')->name('home');
Route::get('/home', 'ReportController@index')->name('home');
Route::get('/userreport/{id}', 'ReportController@userReport')->name('userreport');
Route::post('/userreport/{id}', 'ReportController@userReport')->name('userreport');
Route::get('/period', 'ReportController@periodReport')->name('period');
Route::post('/period', 'ReportController@periodReport')->name('period');
Route::post('/reportController', 'ReportController@formReport');
Route::get('/change-date-session', 'ReportController@changeSession');
Route::get('/userAdmin', 'ReportController@userAdmin')->name('userAdmin');
Route::post('/userEdit', 'ReportController@userEdit');
Route::get('/newKeyAdmin', 'ReportController@newKeyAdmin')->name('newKeyAdmin');
Route::post('/newKeyAdmin', 'ReportController@newKeyAdmin')->name('newKeyAdmin');
Route::get('/deleteNewKey/{id}', 'ReportController@deleteNewKey');
Route::post('/addNewUser', 'ReportController@addNewUser');
