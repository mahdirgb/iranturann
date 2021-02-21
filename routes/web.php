<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\services\Notifications\Notification;
use App\User;

Route::get('/', function () {
    // $sms = resolve(Notification::class);
    // $user = User::find(1);
    // $sms->sendSms($user,2222);
    // return auth()->user()->activeCode()->create([
    //     'code' => 23,
    //     'expired_at' => now()->addMinutes(1)
    // ]);
    //   return view('index');
    dd(auth()->user()->can('add user'));
});
Route::namespace('Auth')->group(function () {
Route::get('logout/', 'LoginController@logout')->name('logout');
Route::get('login/', 'LoginController@showLoginForm');
Route::post('login/', 'LoginController@login')->name('login');
Route::post('register/', 'RegisterController@register')->name('register');
Route::get('register/', 'RegisterController@showRegistrationForm');
});
Route::namespace('Auth\AuthCode')->group(function () {
    Route::get('loginwithcode/' , 'LoginWithCodeController@showLoginForm');
    Route::post('loginwithcode/', 'LoginWithCodeController@login')->name('login_with_code');
    Route::get('verify', 'LoginWithCodeController@verifyForm')->name('verify_login_code');
    Route::post('verify', 'LoginWithCodeController@codeValidator')->name('validate_code');
});