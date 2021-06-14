<?php

use Illuminate\Support\Facades\Route;

// prefix: messages/
Route::get('/prueba/{loveone_slug}', 'MessageController@prueba')->name('messages.prueba');

Route::get('/{loveone_slug}', 'MessagesController@index')->name('messages');
Route::get('/careteam/{loveone_slug}', 'MessagesController@getCareteam')->name('messages.careteam');
Route::get('/chats/{loveone_slug}', 'MessagesController@getChats')->name('messages.chats');
Route::get('/chat/{id}', 'MessagesController@getChat')->name('messages.chat');
Route::post('/chat', 'MessagesController@storeMessage')->name('messages.store');
Route::post('/chat/new/{loveone_slug}', 'MessagesController@newChat')->name('messages.chat.new');

Route::get('/messages/{selected_user}/{loveone_id}', 'MessagesController@index')->name('messages.chat2');
