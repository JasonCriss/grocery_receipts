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
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/receipts', 'ReceiptController@index');
Route::get('/receipts/create', 'ReceiptController@create');
Route::post('/receipts/create', 'ReceiptController@store');

Route::get('/reports', 'ReportController@index');
Route::get('/reports/productsovertime', 'ReportController@productsovertime');
Route::post('/reports/productsovertime', 'ReportController@productsovertime');
Route::get('/reports/producttypeovertime', 'ReportController@producttypeovertime');
Route::post('/reports/producttypeovertime', 'ReportController@producttypeovertime');
Route::get('/reports/costsbymonth', 'ReportController@costsbymonth');
Route::get('/reports/charts', 'ReportController@monthlycharts');

Route::get('/categories','CategoryController@index');

Route::get('/products','ProductController@index');
Route::post('/products','ProductController@changetypes');

Route::get('/init/{id}', 'SystemController@init');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

