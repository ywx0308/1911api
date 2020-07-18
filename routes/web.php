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

Route::get('/', function () {
    return view('welcome');
});

//注册
Route::post('/test/reg','Test\TestController@reg');
//登录
Route::post('/test/login','Test\TestController@login');
//个人中心
Route::post('/test/conter','Test\TestController@conter');


