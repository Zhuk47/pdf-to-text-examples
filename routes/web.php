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

// Test OCR routes
Route::get('test/ocr/', 'OCRController@index')->name('test.index');
Route::post('test/ocr/', 'OCRController@store')->name('test.store');
