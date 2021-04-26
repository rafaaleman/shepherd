<?php

use Illuminate\Support\Facades\Route;

// prefix: loveone/
// Route::get('/', 'CareteamController@index')->name('careteam');
Route::get('/{loveone_slug}', 'CareteamController@index')->name('careteam');
