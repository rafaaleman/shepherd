<?php

use Illuminate\Support\Facades\Route;

// prefix: carehub/
Route::get('/{loveone_slug}', 'EventController@index')->name('carehub');
Route::get('/event/create/{loveone_slug}', 'EventController@createForm')->name('carehub.event.form.create');
Route::post('/event/create', 'EventController@createUpdate')->name('carehub.event.create');

Route::get('/getEvents/{loveone_slug}/{date}/{type}', 'EventController@getEvents')->name('carehub.getEvents');
Route::post('/event', 'EventController@getEvent')->name('carehub.getEvent');
Route::get('/getCalendar/{month}', 'EventController@getCalendar')->name('carehub.getCalendar');

//Messages
Route::post('/event/message/create', 'MessageController@createMessage')->name('carehub.event.message.create');
