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
Route::resource('cart', 'CartController');
Route::post('/tes', 'HomeController@tes');
Route::get('/cetak', 'HomeController@cetak');
Route::resource('/faktur', 'FakturController');
Route::get('/faktur/cetak/{id}', 'FakturController@cetak');
Route::delete('/faktur/delete/{id}', 'FakturController@destroy');
Route::get('/items/create', 'ItemsController@create');
Route::put('/items/update/{id}', 'ItemsController@update');
Route::delete('/items/delete/{id}', 'ItemsController@destroy');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
