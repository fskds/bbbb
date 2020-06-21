<?php

use Illuminate\Support\Facades\Route;

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

Route::namespace('Admin')->group(function() {
    // 登录、注销
    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');
});

Route::namespace('Admin')->middleware('auth.admin')->group(function() {
    // 后台布局
    Route::get('/', 'IndexController@layout')->name('admin.layout');
    // 后台首页
    Route::get('/index', 'IndexController@index')->name('admin.index');

});




// Route::group(['prefix' => 'admin','namespace' => 'Admin'], function() {

        // Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
        // Route::post('/login', 'LoginController@login');
        // Route::get('/logout', 'LoginController@logout')->name('admin.logout');

	// Route::group(['middleware' => 'auth.admin'], function () {
        // Route::get('/', 'IndexController@index');
    // });


// });
