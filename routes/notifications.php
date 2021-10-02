<?php

use Illuminate\Support\Facades\Route;

// prefix: notifications/
Route::get('/{slug}', 'NotificationController@index')->name('notifications');
Route::post('/readNotification', 'NotificationController@readNotification')->name('notifications.readNotification');
