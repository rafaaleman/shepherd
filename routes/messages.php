<?php

use Illuminate\Support\Facades\Route;

// prefix: messages/
Route::get('/prueba/{loveone_slug}', 'MessageController@prueba')->name('messages.prueba');
Route::get('/{loveone_slug}', 'MessagesController@index')->name('messages');


Route::get('/careteam/{loveone_slug}', 'MessagesController@getCareteam')->name('messages.careteam');
Route::get('/chats/{loveone_slug}', 'MessagesController@getChats')->name('messages.chats');
Route::get('/chat/{id}', 'MessagesController@getChat')->name('messages.chat');


Route::get('/chat/delete/{id}', 'MessagesController@deleteChat')->name('messages.chat.delete');
Route::get('/chat/delete/message/{id}', 'MessagesController@deleteMessage')->name('messages.delete');
Route::post('/chat/2', 'MessagesController@storeMessage')->name('messages.stores');
Route::post('/chat/new/{loveone_slug}', 'MessagesController@newChat')->name('messages.chat.new');
Route::post('/chat/lastmessages', 'MessagesController@lastMessages')->name('messages.last');

Route::get('/messages/{selected_user}/{loveone_id}', 'MessagesController@index')->name('messages.chat2');
