<?php

use Illuminate\Support\Facades\Route;

// prefix: loveone/
Route::get('/', 'LoveoneController@index')->name('loveone');
Route::post('/setLoveone', 'LoveoneController@setLoveone')->name('loveone.setLoveone');
Route::post('/create', 'LoveoneController@createUpdate')->name('loveone.create');
Route::get('/{loveone_slug}', 'LoveoneController@edit')->name('loveone.edit');
