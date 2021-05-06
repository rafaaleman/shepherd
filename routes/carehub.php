<?php

use Illuminate\Support\Facades\Route;

// prefix: carehub/
Route::get('/{loveone_slug}', 'EventController@index')->name('carehub');
Route::get('/event/create/{loveone_slug}', 'EventController@createForm')->name('carehub.event.form.create');
Route::post('/event/create', 'EventController@createUpdate')->name('carehub.event.create');
//Route::get('/{careteam}', 'LoveoneController@edit')->name('loveone.edit');
