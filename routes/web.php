<?php

use Illuminate\Http\Request;

use App\Mail\sendJoinTeamMail;
use App\Mail\sendInvitationMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if (!$request->secure() && App::environment() === 'production'){
        return redirect()->secure($request->getRequestUri());
    } else {
        return view('welcome');
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
Route::get('/user/profile', 'HomeController@profile')->name('user.profile')->middleware('auth','two_factor');

Route::get('/register/{token}', 'Auth\RegisterController@showRegistrationForm2')->name('register_invitation');
Route::get('/home', 'HomeController@index')->name('home')->middleware('two_factor');

Route::get('/new', 'HomeController@newUser')->name('new');

Route::get("/resources/{loveone_slug}","ResourceController@getTopics")->name("resources")->middleware('two_factor','auth');
Route::get("/resources/home/{loveone_slug}","ResourceController@getTopicsCarehub")->name("resources.carehub");


Route::post("/resources/search","ResourceController@getTopicsSearch")->middleware('auth')->name("resources.search");
Route::post("/resources/search/ini","ResourceController@getTopicsSearchIni")->middleware('auth')->name("resources.ini");


Route::view('/terms', 'terms');
Route::view('/privacy', 'privacy');


Route::post("/readTour","HomeController@readTour")->name("readTour");

Route::get('verify/resend', 'Auth\TwoFactorController@resend')->name('verify.resend');
Route::get('verify/lockbox', 'Auth\TwoFactorController@lockbox')->name('verify.lockbox');
Route::post('verify/lockbox_store', 'Auth\TwoFactorController@lockbox_store')->name('verify.lockbox_store');
Route::post('verify/cancel', 'Auth\TwoFactorController@lockbox_cancel')->name('verify.lockbox_cancel');
Route::resource('verify', 'Auth\TwoFactorController')->only(['index', 'store']);