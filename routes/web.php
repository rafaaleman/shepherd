<?php

use Illuminate\Support\Facades\Route;

use App\Mail\sendJoinTeamMail;
use App\Mail\sendInvitationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    if (request()->secure()){
        return view('welcome');
    } else {
        return redirect()->secure($request->getRequestUri());
    }
});

Route::get('/prueba', function () {
    // send email
    $details = [
        'url' => 'prueba.com',
        'role' => '2',
        'loveone_name' => 'mi abuelito',
        'loveone_photo' => 'no_hay.jpg',
    ];

    Mail::to('karonte.von.ravnos@gmail.com')->send(new sendInvitationMail($details));
    echo "holi";
});

Auth::routes([
    'register' => true,
    'verify' => true,
    'reset' => true
]);

Route::post('/user/profile/update', 'Auth\RegisterController@updateUser')->name('user.profile.update')->middleware('auth');
Route::get('/user/profile', 'HomeController@profile')->name('user.profile')->middleware('auth');

Route::get('/register/{token}', 'Auth\RegisterController@showRegistrationForm2')->name('register_invitation');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/new', 'HomeController@newUser')->name('new');

Route::get("/resources/{loveone_slug}","ResourceController@getTopics")->name("resources");
Route::get("/resources/home/{loveone_slug}","ResourceController@getTopicsCarehub")->name("resources.carehub");


Route::post("/resources/search","ResourceController@getTopicsSearch")->name("resources.search");
Route::post("/resources/search/ini","ResourceController@getTopicsSearchIni")->name("resources.ini");


Route::view('/terms', 'terms');
Route::view('/privacy', 'privacy');


