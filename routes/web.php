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
<<<<<<< HEAD
//注册
Route::post('/test/reg','Test\TestController@reg');
//登录
Route::post('/test/login','Test\TestController@login');
//个人中心
Route::post('/test/conter','Test\TestController@conter');


=======
Route::get('/test/getwxtoken','Test\TestController@getwxtoken');
Route::get('/test/getwxtoken2','Test\TestController@getwxtoken2');
Route::get('/test/getwxtoken3','Test\TestController@getwxtoken3');

Route::get('/test/apiinit','Test\TestController@apiinit');
>>>>>>> 163895baf36f06bcdc34bf512a69275910090bca
