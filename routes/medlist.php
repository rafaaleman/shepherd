<?php

use Illuminate\Support\Facades\Route;

// prefix: medlist/
Route::get('/{loveone_slug}', 'MedlistController@index')->name('medlist');
Route::get('/create/{loveone_slug}', 'MedlistController@createForm')->name('medlist.form.create');
Route::post('/create', 'MedlistController@createUpdate')->name('medlist.create');

Route::get('/getMedications/{loveone_slug}/{date}', 'MedlistController@getMedications')->name('medlist.getMedications');
Route::get('/getCalendar/{month}', 'MedlistController@getCalendar')->name('medlist.getCalendar');

Route::post("/medicine/search","MedlistController@getMedicineSearch")->name("medicine.search");
Route::post("/medicine/route/search","MedlistController@getRouteSearch")->name("medicine.route.search");
Route::post("/medicine/route/strength/search","MedlistController@getStrengthSearch")->name("medicine.route.strength.search");
Route::post("/medicine/search/wiki","MedlistController@getWikiSearch")->name("medicine.search.wiki");
