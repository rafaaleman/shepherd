<?php

use Illuminate\Support\Facades\Route;

// prefix: messages/
Route::get('/prueba/{loveone_slug}', 'MessageController@prueba')->name('messages.prueba');

Route::get('/{loveone_slug}', 'MessagesController@index')->name('messages');
