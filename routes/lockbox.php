<?php

use Illuminate\Support\Facades\Route;

// prefix: lockbox/
Route::get('/{loveone_slug}', 'LockboxController@index')->name('lockbox');
Route::post('/store', 'LockboxController@store')->name('lockbox.store');
Route::post('/update', 'LockboxController@update')->name('lockbox.update');
Route::post('/delete', 'LockboxController@destroy')->name('lockbox.delete');

Route::get('/lastDocuments/{loveone_slug}', 'LockboxController@lastDocuments')->name('lockbox.lastDocuments');
//Route::post('/create', 'LockboxController@createUpdate')->name('lockbox.create');
//Route::post('/store', 'LockboxController@store')->name('lockbox.store');