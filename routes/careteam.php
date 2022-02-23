<?php

use Illuminate\Support\Facades\Route;

// prefix: careteam/
Route::get('/', 'CareteamController@index')->name('careteam');
Route::post('/getMembers', 'CareteamController@getCareteamMembers')->name('careteam.getCareteamMembers');

// Add new member
Route::get('/{loveone_slug}/new-member', 'CareteamController@createNewMember')->name('careteam.createNewMember');

// Edit member
Route::post('/{loveone_slug}/edit-member', 'CareteamController@editMember')->name('careteam.editMember');

Route::post('/saveNewMember', 'CareteamController@saveNewMember')->name('careteam.saveNewMember');
Route::post('/updateMemberPermissions', 'CareteamController@updateMemberPermissions')->name('careteam.updateMemberPermissions');
Route::post('/deleteMember', 'CareteamController@deleteMember')->name('careteam.deleteMember');
Route::post('/changeStatus', 'CareteamController@changeStatus')->name('careteam.changeStatus');

Route::post('/searchMember', 'CareteamController@searchMember')->name('careteam.searchMember');
Route::post('/inlcudeAMember', 'CareteamController@inlcudeAMember')->name('careteam.inlcudeAMember');
Route::post('/sendInvitation', 'CareteamController@sendInvitation')->name('careteam.sendInvitation');
Route::post('/deleteInvitation', 'CareteamController@deleteInvitation')->name('careteam.deleteInvitation');
Route::post('/acceptInvitation', 'CareteamController@acceptInvitation')->name('careteam.acceptInvitation');


Route::get('/joinTeam', 'CareteamController@joinTeam')->name('careteam.joinTeam');
Route::get('/getInvitations', 'CareteamController@getInvitations')->name('careteam.getInvitations');


Route::get('/{loveone_slug}', 'CareteamController@index')->name('careteam');