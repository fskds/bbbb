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
	// 图标
    Route::get('icons', 'IndexController@icons')->name('admin.icons');
});

Route::namespace('Admin')->middleware(['auth:admin', 'permission:system.manage'])->group(function() {
  
	// 数据表格接口
    Route::get('data', 'IndexController@data')->name('admin.data')->middleware('permission:system.role|system.admin|system.permission');

    // 用户管理
    Route::prefix('admin')->group(function() {
        Route::get('index', 'UserController@index')->name('admin.admin');
        // 添加
        Route::get('create', 'UserController@create')->name('admin.admin.create')->middleware('permission:system.admin.create');
        Route::post('store', 'UserController@store')->name('admin.admin.store')->middleware('permission:system.admin.create');
        // 编辑
        Route::get('{id}/edit', 'UserController@edit')->name('admin.admin.edit')->middleware('permission:system.admin.edit');
        Route::put('{id}/update', 'UserController@update')->name('admin.admin.update')->middleware('permission:system.admin.edit');
        // 删除
        Route::delete('destroy', 'UserController@destroy')->name('admin.admin.destroy')->middleware('permission:system.admin.destroy');
        // 分配角色
        Route::get('{id}/role', 'UserController@role')->name('admin.admin.role')->middleware('permission:system.admin.role');
        Route::put('{id}/assignRole', 'UserController@assignRole')->name('admin.admin.assignRole')->middleware('permission:system.admin.role');
        // 分配权限
        Route::get('{id}/permission', 'UserController@permission')->name('admin.admin.permission')->middleware('permission:system.admin.permission');
        Route::put('{id}/assignPermission', 'UserController@assignPermission')->name('admin.admin.assignPermission')->middleware('permission:system.admin.permission');
    });

    // 角色管理
    Route::prefix('role')->middleware(['permission:system.role'])->group(function() {
        Route::get('index', 'RoleController@index')->name('admin.role');
        // 添加
        Route::get('create', 'RoleController@create')->name('admin.role.create')->middleware('permission:system.role.create');
        Route::post('store', 'RoleController@store')->name('admin.role.store')->middleware('permission:system.role.create');
        // 编辑
        Route::get('{id}/edit', 'RoleController@edit')->name('admin.role.edit')->middleware('permission:system.role.edit');
        Route::put('{id}/update', 'RoleController@update')->name('admin.role.update')->middleware('permission:system.role.edit');
        // 删除
        Route::delete('destroy', 'RoleController@destroy')->name('admin.role.destroy')->middleware('permission:system.role.destroy');
        // 分配权限
        Route::get('{id}/permission', 'RoleController@permission')->name('admin.role.permission')->middleware('permission:system.role.permission');
        Route::put('{id}/assignPermission', 'RoleController@assignPermission')->name('admin.role.assignPermission')->middleware('permission:system.role.permission');
    });

    // 权限管理
    Route::prefix('permission')->middleware(['permission:system.permission'])->group(function() {
        Route::get('index', 'PermissionController@index')->name('admin.permission');
        // 添加
        Route::get('create', 'PermissionController@create')->name('admin.permission.create')->middleware('permission:system.permission.create');
        Route::post('store', 'PermissionController@store')->name('admin.permission.store')->middleware('permission:system.permission.create');
        // 编辑
        Route::get('{id}/edit', 'PermissionController@edit')->name('admin.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('{id}/update', 'PermissionController@update')->name('admin.permission.update')->middleware('permission:system.permission.edit');
        // 删除
        Route::delete('destroy', 'PermissionController@destroy')->name('admin.permission.destroy')->middleware('permission:system.permission.destroy');
    });

    // 操作日志
    Route::prefix('log')->middleware(['permission:system.log'])->group(function() {
        Route::get('index', 'LogController@index')->name('admin.log');
        // 数据表格接口
        Route::get('data', 'LogController@data')->name('admin.log.data')->middleware('permission:system.log|system.log');
        // 添加
        Route::get('create', 'LogController@create')->name('admin.log.create')->middleware('permission:system.log.create');
        Route::post('store', 'LogController@store')->name('admin.log.store')->middleware('permission:system.log.create');
        // 编辑
        Route::get('{id}/edit', 'LogController@edit')->name('admin.log.edit')->middleware('permission:system.log.edit');
        Route::put('{id}/update', 'LogController@update')->name('admin.log.update')->middleware('permission:system.log.edit');
        // 删除
        Route::delete('destroy', 'LogController@destroy')->name('admin.log.destroy')->middleware('permission:system.log.destroy');
    });
});
