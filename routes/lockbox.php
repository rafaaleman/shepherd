<?php

use Illuminate\Support\Facades\Route;

// prefix: lockbox/
Route::get('/', 'LockboxController@index')->name('lockbox');
Route::post('/', 'LockboxController@store')->name('lockbox.store');
Route::post('/update', 'LockboxController@update')->name('lockbox.update');
//Route::post('/create', 'LockboxController@createUpdate')->name('lockbox.create');
//Route::post('/store', 'LockboxController@store')->name('lockbox.store');
