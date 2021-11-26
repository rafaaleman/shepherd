<?php

use Illuminate\Support\Facades\Route;

// prefix: carehub/
Route::get('/{loveone_slug}', 'EventController@index')->name('carehub');
Route::get('/event/create/{loveone_slug}', 'EventController@createForm')->name('carehub.event.form.create');
Route::post('/event/create', 'EventController@createUpdate')->name('carehub.event.create');

Route::get('/discussion/create/{loveone_slug}', 'DiscussionController@createForm')->name('carehub.discussion.form.create');
Route::post('/discussion/create', 'DiscussionController@createUpdate')->name('carehub.discussion.create');
Route::get('/getDiscussions/{loveone_slug}', 'DiscussionController@getDiscussions')->name('carehub.getDiscussions');
Route::post('/discussion/delete', 'DiscussionController@archiveDiscussion')->name('carehub.discussion.delete');
Route::post('/discussion', 'DiscussionController@getDiscussion')->name('carehub.getDiscussion');


Route::get('/getEvents/{loveone_slug}/{date}/{type}', 'EventController@getEvents')->name('carehub.getEvents');
Route::post('/event', 'EventController@getEvent')->name('carehub.getEvent');
Route::get('/getCalendar/{month}', 'EventController@getCalendar')->name('carehub.getCalendar');
Route::post('/event/delete', 'EventController@deleteEvent')->name('carehub.event.delete');

//Messages
Route::post('/event/message/create', 'MessageController@createMessage')->name('carehub.event.message.create');
Route::post('/discussion/message/create', 'MessageController@createMessageDiscussion')->name('carehub.discussion.message.create');
