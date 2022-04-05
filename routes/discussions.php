<?php

use Illuminate\Support\Facades\Route;

// prefix: discussions/
Route::get('/prueba/{loveone_slug}', 'MessageController@prueba')->name('discussions.prueba');
Route::get('/{loveone_slug}', 'MessagesController@index')->name('discussions');
Route::get('/selected/{loveone_slug}/{discussions}', 'MessagesController@index')->name('discussions.selected');
Route::get('/create/{loveone_slug}', 'MessagesController@create')->name('discussions.create');
Route::post('/store', 'MessagesController@store')->name('discussions.store');

Route::get('/chats/{loveone_slug}', 'MessagesController@getDiscussions')->name('discussions.chats');
Route::get('/chat/{id}', 'MessagesController@getChat')->name('discussions.chat');

Route::post('/message/store', 'MessagesController@storeMessage')->name('messages.store');