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

use Illuminate\Support\Facades\Route;

Route::post('image-upload', 'SharedController@imageUpload')->name('image-upload');
Route::get('image-remove/{id}/{folder}', 'SharedController@imageRemove')->name('image-remove');
Route::get('lang/{locale}', 'SharedController@lang')->name('lang');
