<?php

use Illuminate\Support\Facades\Route;

// prefix: notifications/
Route::get('/{slug}', 'NotificationController@index')->name('notifications');
// Route::post('/setLoveone', 'NotificationsController@setLoveone')->name('loveone.setLoveone');
// Route::post('/create', 'NotificationsController@createUpdate')->name('loveone.create');
// Route::get('/{loveone}', 'NotificationsController@edit')->name('loveone.edit');
