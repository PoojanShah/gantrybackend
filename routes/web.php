<?php

use App\Http\Controllers\MailGunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/subscriptions', '\App\Http\Controllers\Admin\SubscriptionController@index');
Route::get('/admin/subscriptions/{id}', '\App\Http\Controllers\Admin\SubscriptionController@show');

//Videos
Route::get('/admin/video/', '\App\Http\Controllers\Admin\VideoController@video');
Route::get('/admin/video/delete/', '\App\Http\Controllers\Admin\VideoController@videoDelete');
Route::get('/admin/video/edit/{id?}', '\App\Http\Controllers\Admin\VideoController@videoEdit');
Route::post('/admin/video/edit/{id?}', '\App\Http\Controllers\Admin\VideoController@videoPostEdit');
Route::get('/admin/video/add/', '\App\Http\Controllers\Admin\VideoController@videoAdd');
Route::post('/admin/video/add/', '\App\Http\Controllers\Admin\VideoController@videoPostAdd');


//Users
Route::get('/admin/users/', '\App\Http\Controllers\Admin\UsersController@users');
Route::get('/admin/users/delete/', '\App\Http\Controllers\Admin\UsersController@usersDelete');
Route::get('/admin/users/add/', '\App\Http\Controllers\Admin\UsersController@usersAdd');
Route::post('/admin/users/add/', '\App\Http\Controllers\Admin\UsersController@usersPostAdd');
Route::get('/admin/users/edit/{id?}', '\App\Http\Controllers\Admin\UsersController@usersEdit');
Route::post('/admin/users/edit/{id?}', '\App\Http\Controllers\Admin\UsersController@usersPostEdit');


Route::get('/admin/profile', '\App\Http\Controllers\Admin\ProfileController@index')->name('profile');
Route::post('/admin/profile', '\App\Http\Controllers\Admin\ProfileController@index')->name('profile');
Route::get('/admin/change_profile', '\App\Http\Controllers\Admin\ProfileController@change');

//Route::get('/forgot', '\App\Http\Controllers\Auth\ForgotController@index')->name('forgot');
//Route::post('/forgot', '\App\Http\Controllers\Auth\ForgotController@index')->name('forgot');
//Route::get('/change_password', '\App\Http\Controllers\Auth\ForgotController@change');

//Dashboard
Route::get('/admin/', [AdminController::class, 'index'])->name('admin');
Route::get('/admin/tokens', [AdminController::class, 'zohoTokensManagement'])->name('zohoTokensManagement');
Route::get('/home/', function () {
    return redirect('/admin/');
});


Route::get('/admin/login/', [
    'as' => 'login',
    'uses' => '\App\Http\Controllers\Auth\LoginController@showLoginForm'
]);
Route::get('/login/', [
    'as' => 'login',
    'uses' => '\App\Http\Controllers\Auth\LoginController@showLoginForm'
]);
Route::post('/admin/login/', [
    'as' => '',
    'uses' => '\App\Http\Controllers\Auth\LoginController@login'
]);
Route::post('/admin/logout/', [
    'as' => 'logout',
    'uses' => '\App\Http\Controllers\Auth\LoginController@logout'
]);
Route::get('/admin/logout/', [
    'as' => 'logout',
    'uses' => '\App\Http\Controllers\Auth\LoginController@logout'
]);

Route::get('password/reset', '\App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', '\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', '\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
//Route::get('/send-mail-using-mailgun', [MailGunController::class, 'index'])->name('send.mail.using.mailgun.index');
