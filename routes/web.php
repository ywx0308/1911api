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
Route::get('/test/conter','Test\TestController@conter')->Middleware('auth.token','count');
//redis
Route::get('/test/hash','Test\TestController@hash');
Route::post('/test/hash2','Test\TestController@hash2');
//商品详情
Route::get('/test/goods','Goods\GoodsController@info')->Middleware('auth.token','count');
//对称解密
Route::any('/test/decrypt','Test\TestController@decrypt');
//非对称解密1911项目秘钥
Route::any('/test/no_decrypt','Test\TestController@no_decrypt');
Route::get('/test/no_encrypt','Test\TestController@no_encrypt');
//签名
Route::get('/test/name2','Test\TestController@name2');
//签名解密
Route::get('/test/nam_decrypt','Test\TestController@nam_decrypt');

//H5商城
Route::get('/api/reg','Api\ApiController@reg');//注册
Route::get('/api/login','Api\ApiController@login');//登录

