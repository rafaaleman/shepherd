<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => true,
    'verify' => true,
    'reset' => false
]);

Route::post('/user/profile/update', 'Auth\RegisterController@updateUser')->name('user.profile.update')->middleware('auth');
Route::get('/user/profile', 'HomeController@profile')->name('user.profile')->middleware('auth');

Route::get('/register/{token}', 'Auth\RegisterController@showRegistrationForm2')->name('register_invitation');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/new', 'HomeController@newUser')->name('new');

Route::get("/resources/{loveone_slug}","ResourceController@getTopics")->name("resources");
Route::post("/resources/search","ResourceController@getTopicsSearch")->name("resources.search");
Route::post("/resources/search/ini","ResourceController@getTopicsSearchIni")->name("resources.ini");

