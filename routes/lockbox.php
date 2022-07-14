<?php

use Illuminate\Support\Facades\Route;

// prefix: lockbox/
Route::get('/test', 'LockboxController@test')->name('lockbox.test');
//Route::get('/verify/{loveone_slug}', 'LockboxController@index')->name('lockbox.index');
Route::get('/{loveone_slug}', 'LockboxController@index')->name('lockbox.index');
Route::post('/store', 'LockboxController@store')->name('lockbox.store');
Route::post('/update', 'LockboxController@update')->name('lockbox.update');
Route::post('/delete', 'LockboxController@destroy')->name('lockbox.delete');

Route::get('/lastDocuments/{loveone_slug}', 'LockboxController@lastDocuments')->name('lockbox.lastDocuments');
Route::get('/countDocuments/{loveone_slug}', 'LockboxController@countDocuments')->name('lockbox.countDocuments');
//Route::post('/create', 'LockboxController@createUpdate')->name('lockbox.create');
//Route::post('/store', 'LockboxController@store')->name('lockbox.store');

Route::get('/documents/check', 'LockboxController@checkEssentialDocuments')->name('lockbox.checkDocuments');
Route::get('/document/{id_file}', 'LockboxController@downloadFile')->name('lockbox.downloadFile');

Route::get('/prueba/{loveone_slug}', 'LockboxController@prueba')->name('lockbox.prueba');