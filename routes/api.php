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


// v1 前台
Route::group(['prefix' => 'v1'], function ($router) {
    Route::get('index', function(){
        return 'api v1 works';
    });
    // 用户登录注册
    Route::post('register',[App\Http\Controllers\User\V1\AuthJwtController::class, 'register']);
    Route::post('login', [App\Http\Controllers\User\V1\AuthJwtController::class, 'login']);
    Route::group(['middleware'=>'refresh.token'], function(){
        // 获取token 获取用户信息
        Route::post('logout', [App\Http\Controllers\User\V1\AuthJwtController::class, 'logout']);
        Route::post('refresh', [App\Http\Controllers\User\V1\AuthJwtController::class, 'refresh']);
        Route::get('me', [App\Http\Controllers\User\V1\AuthJwtController::class, 'me']);

    });

});