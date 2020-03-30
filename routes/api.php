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

Route::group(['middleware' => ['api']], function () {
    //ユーザー登録
    Route::post('/createuser', 'UsersController@create');


    //ログイン
    Route::post('/login', 'Auth\AuthController@login');

    //トップページ
    Route::get('/', function () {
        return response()->json('go_top');
    })->name('login');

    //認証必須
    Route::group(['middleware' => ['jwt.auth']], function () {
        //ログアウト
        Route::post('/logout', 'Auth\AuthController@logout');
        //トークンリフレッシュ
        Route::post('/refresh', 'Auth\AuthController@refresh');
        //自ユーザー情報取得
        Route::post('/me', 'Auth\AuthController@me');

        //ユーザー情報の更新
        Route::post('/updateuser', 'UsersController@update');
    });
});
