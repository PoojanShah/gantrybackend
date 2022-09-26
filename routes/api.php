<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('media', '\App\Http\Controllers\Api\VideoController@getMedia');
Route::get('videos', '\App\Http\Controllers\Api\VideoController@getVideos');
Route::get('messages', '\App\Http\Controllers\Api\VideoController@getMessages');
Route::post('subscriptions', '\App\Http\Controllers\Api\SubscriptionController@createOrUpdate')->middleware('zoho.hook.auth');
Route::get('installations/{installation_id}/subscription', '\App\Http\Controllers\Api\SubscriptionController@getClientSubscription');
