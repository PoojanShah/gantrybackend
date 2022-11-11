<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/subscriptions', '\App\Http\Controllers\Admin\SubscriptionController@index')->middleware(['auth'])->name('subscriptions.index');
Route::get('/admin/subscriptions/{id}', '\App\Http\Controllers\Admin\SubscriptionController@show')->middleware(['auth'])->name('subscriptions.show');
Route::get('/admin/subscriptions/{id}/activate', '\App\Http\Controllers\Admin\SubscriptionController@activate')->middleware(['auth'])->name('subscriptions.activate');

Route::get('/admin/library', '\App\Http\Controllers\Admin\LibraryController@index')->middleware(['auth'])->name('library.index');
Route::get('/admin/library/{media}', '\App\Http\Controllers\Admin\LibraryController@show')->middleware(['auth'])->name('library.show');
Route::post('/admin/library/{media}/subscribe', '\App\Http\Controllers\Admin\LibraryController@buyOneTimeAddon')->middleware(['auth'])->name('library.buyOneTimeAddon');

//Videos
Route::get('/admin/video/', '\App\Http\Controllers\Admin\VideoController@video')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::get('/admin/video/delete/', '\App\Http\Controllers\Admin\VideoController@videoDelete')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::get('/admin/video/edit/{id?}', '\App\Http\Controllers\Admin\VideoController@videoEdit')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::post('/admin/video/edit/{id?}', '\App\Http\Controllers\Admin\VideoController@videoPostEdit')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::get('/admin/video/add/', '\App\Http\Controllers\Admin\VideoController@videoAdd')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::post('/admin/video/add/', '\App\Http\Controllers\Admin\VideoController@videoPostAdd')->middleware(['auth', 'auth.superAdminAccessOnly']);

//Users
Route::get('/admin/users/', '\App\Http\Controllers\Admin\UsersController@users')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::get('/admin/users/delete/', '\App\Http\Controllers\Admin\UsersController@usersDelete')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::get('/admin/users/add/', '\App\Http\Controllers\Admin\UsersController@usersAdd')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::post('/admin/users/add/', '\App\Http\Controllers\Admin\UsersController@usersPostAdd')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::get('/admin/users/edit/{id?}', '\App\Http\Controllers\Admin\UsersController@usersEdit')->middleware(['auth', 'auth.superAdminAccessOnly'])->name('users.edit');
Route::post('/admin/users/edit/{id?}', '\App\Http\Controllers\Admin\UsersController@usersPostEdit')->middleware(['auth', 'auth.superAdminAccessOnly']);

//Route::get('/admin/profile', '\App\Http\Controllers\Admin\ProfileController@index')->name('profile');
//Route::post('/admin/profile', '\App\Http\Controllers\Admin\ProfileController@index')->name('profile');
//Route::get('/admin/change_profile', '\App\Http\Controllers\Admin\ProfileController@change');

//Route::get('/forgot', '\App\Http\Controllers\Auth\ForgotController@index')->name('forgot');
//Route::post('/forgot', '\App\Http\Controllers\Auth\ForgotController@index')->name('forgot');
//Route::get('/change_password', '\App\Http\Controllers\Auth\ForgotController@change');

//Dashboard
Route::get('/admin/', [AdminController::class, 'index'])->name('admin');
Route::get('/admin/tokens', [AdminController::class, 'zohoTokensManagement'])->name('zohoTokensManagement')->middleware(['auth', 'auth.superAdminAccessOnly']);
Route::get('/home/', function (\Illuminate\Support\Facades\Auth $auth) {
    if(!$auth::user()->isSuperAdmin()){
        return redirect()->route('library.index');
    }

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
