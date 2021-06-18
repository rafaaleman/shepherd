<?php

use Illuminate\Support\Facades\Route;

// prefix: medlist/
Route::get('/{loveone_slug}', 'MedlistController@index')->name('medlist');
Route::get('/create/{loveone_slug}', 'MedlistController@createForm')->name('medlist.form.create');
Route::post('/create', 'MedlistController@createUpdate')->name('medlist.create');

Route::get('/getMedications/{loveone_slug}/{date}', 'MedlistController@getMedications')->name('medlist.getMedications');
Route::get('/getCalendar/{month}', 'MedlistController@getCalendar')->name('medlist.getCalendar');