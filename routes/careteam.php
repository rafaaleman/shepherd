<?php

use Illuminate\Support\Facades\Route;

// prefix: careteam/
// Route::get('/', 'CareteamController@index')->name('careteam');
Route::get('/{loveone_slug}', 'CareteamController@index')->name('careteam');
Route::get('/getMembers/{loveone_slug}', 'CareteamController@getCareteamMembers')->name('careteam.getCareteamMembers');
Route::post('/saveNewMember', 'CareteamController@saveNewMember')->name('careteam.saveNewMember');
Route::post('/updateMemberPermissions', 'CareteamController@updateMemberPermissions')->name('careteam.updateMemberPermissions');
Route::post('/deleteMember', 'CareteamController@deleteMember')->name('careteam.deleteMember');

Route::post('/searchMember', 'CareteamController@searchMember')->name('careteam.searchMember');
Route::post('/inlcudeAMember', 'CareteamController@inlcudeAMember')->name('careteam.inlcudeAMember');
Route::post('/sendInvitation', 'CareteamController@sendInvitation')->name('careteam.sendInvitation');
Route::post('/deleteInvitation', 'CareteamController@deleteInvitation')->name('careteam.deleteInvitation');


Route::get('/join-team', 'CareteamController@joinTeam')->name('joinTeam');