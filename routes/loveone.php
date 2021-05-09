<?php

use Illuminate\Support\Facades\Route;

// prefix: loveone/
Route::get('/', 'LoveoneController@index')->name('loveone');
Route::post('/create', 'LoveoneController@createUpdate')->name('loveone.create');
Route::get('/{careteam}', 'LoveoneController@edit')->name('loveone.edit');
