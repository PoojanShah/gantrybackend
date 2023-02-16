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

Route::get('media', '\App\Http\Controllers\Api\VideoController@getMedia')->middleware('api.token');
Route::get('videos', '\App\Http\Controllers\Api\VideoController@getVideos')->middleware('api.token');
Route::get('messages', '\App\Http\Controllers\Api\VideoController@getMessages')->middleware('api.token');
Route::post('subscriptions', '\App\Http\Controllers\Api\SubscriptionController@createOrUpdate')->middleware(['zoho.hook.auth']);
Route::get('installations/subscription', '\App\Http\Controllers\Api\SubscriptionController@getClientSubscription')->middleware(['api.token', 'header.installationId']);
