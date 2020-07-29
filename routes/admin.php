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

Route::namespace('Backend\Auth')->group(function() {
    // 登录、注销
    Route::get('login', 'LoginController@showLoginForm');
    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');
});
Route::namespace('Backend')->middleware(['auth.admin'])->group(function() {
    // 后台布局
    Route::get('/', 'IndexController@layout')->name('admin.layout');
	// 菜单数据
	Route::get('/init', 'IndexController@getSystemInit')->name('admin.init');
	// 后台首页
    Route::get('/index', 'IndexController@index')->name('admin.index'); 
});

Route::namespace('Backend\Admin')->middleware(['auth:admin','logs','permission:system.admin'])->group(function() {
	
    // 用户管理
    Route::prefix('admin')->group(function() {
        Route::get('/', 'UserController@index')->name('admin.admin');
		// 数据表格接口
		Route::get('data', 'UserController@data')->name('admin.admin.data');
        // 添加
        Route::get('create', 'UserController@create')->name('admin.admin.create')->middleware('permission:system.admin.create');
        Route::post('store', 'UserController@store')->name('admin.admin.store')->middleware('permission:system.admin.create');
        // 编辑
        Route::get('{id}/edit', 'UserController@edit')->name('admin.admin.edit')->middleware('permission:system.admin.edit');
        Route::put('{id}/update', 'UserController@update')->name('admin.admin.update')->middleware('permission:system.admin.edit');
        // 删除
        Route::delete('destroy', 'UserController@destroy')->name('admin.admin.destroy')->middleware('permission:system.admin.destroy');
		Route::delete('restore', 'UserController@restore')->name('admin.admin.restore');
        // 分配角色
        Route::get('{id}/role', 'UserController@role')->name('admin.admin.role')->middleware('permission:system.admin.role');
        Route::put('{id}/assignRole', 'UserController@assignRole')->name('admin.admin.assignRole')->middleware('permission:system.admin.role');
        // 分配权限
        Route::get('{id}/permission', 'UserController@permission')->name('admin.admin.permission')->middleware('permission:system.admin.permission');
        Route::put('{id}/assignPermission', 'UserController@assignPermission')->name('admin.admin.assignPermission')->middleware('permission:system.admin.permission');
    });
});
Route::namespace('Backend\Admin')->middleware(['auth:admin','logs','permission:system.role'])->group(function() {
    // 角色管理
    Route::prefix('role')->group(function() {
        Route::get('/', 'RoleController@index')->name('admin.role');
        // 数据表格接口
		Route::get('data', 'RoleController@data')->name('admin.role.data');
        // 添加
        Route::get('create', 'RoleController@create')->name('admin.role.create')->middleware('permission:system.role.create');
        Route::post('store', 'RoleController@store')->name('admin.role.store')->middleware('permission:system.role.create');
        // 编辑
        Route::get('{id}/edit', 'RoleController@edit')->name('admin.role.edit')->middleware('permission:system.role.edit');
        Route::put('{id}/update', 'RoleController@update')->name('admin.role.update')->middleware('permission:system.role.edit');
        // 删除
        Route::delete('destroy', 'RoleController@destroy')->name('admin.role.destroy')->middleware('permission:system.role.destroy');
        Route::delete('restore', 'RoleController@restore')->name('admin.role.restore')->middleware('permission:system.role.destroy');
        // 分配权限
        Route::get('{id}/permission', 'RoleController@permission')->name('admin.role.permission')->middleware('permission:system.role.permission');
        Route::put('{id}/assignPermission', 'RoleController@assignPermission')->name('admin.role.assignPermission')->middleware('permission:system.role.permission');
    });
});
Route::namespace('Backend\Admin')->middleware(['auth:admin','logs','permission:system.permission'])->group(function() {
    // 权限管理
    Route::prefix('permission')->group(function() {
        Route::get('/', 'PermissionController@index')->name('admin.permission');
        // 数据表格接口
		Route::get('data', 'PermissionController@data')->name('admin.permission.data');
        // 添加
        Route::get('create', 'PermissionController@create')->name('admin.permission.create')->middleware('permission:system.permission.create');
        Route::post('store', 'PermissionController@store')->name('admin.permission.store')->middleware('permission:system.permission.create');
        // 编辑
        Route::get('{id}/edit', 'PermissionController@edit')->name('admin.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('{id}/update', 'PermissionController@update')->name('admin.permission.update')->middleware('permission:system.permission.edit');
        // 删除
        Route::delete('destroy', 'PermissionController@destroy')->name('admin.permission.destroy')->middleware('permission:system.permission.destroy');
        Route::delete('restore', 'PermissionController@restore')->name('admin.permission.restore')->middleware('permission:system.permission.destroy');
    });
});
Route::namespace('Backend\Log')->middleware(['auth:admin','logs','permission:system.log'])->group(function() {
    // 操作日志
    Route::prefix('log')->group(function() {
        Route::get('/', 'LogController@index')->name('admin.log');
        // 日记表格接口
        Route::get('data', 'LogController@data')->name('admin.log.data');
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
Route::namespace('Backend\Sql')->middleware(['auth:admin','logs','permission:system.sql'])->group(function() {
    Route::prefix('sql')->group(function() {
        Route::get('/', 'SqlController@index')->name('system.sql');
        // 备份表格接口
        Route::get('data', 'SqlController@data')->name('system.sql.data');
        // 添加
        Route::post('store', 'SqlController@store')->name('system.sql.store')->middleware('permission:system.sql.store');
        // 编辑
        Route::post('recover', 'SqlController@recover')->name('system.sql.recover')->middleware('permission:system.sql.recover');
        Route::post('download', 'SqlController@download')->name('system.sql.download')->middleware('permission:system.sql.download');
        // 删除
        Route::delete('destroy', 'SqlController@destroy')->name('system.sql.destroy')->middleware('permission:system.sql.destroy');
    });
    
});
Route::namespace('Backend\Nav')->middleware(['auth:admin','logs','permission:site.nav'])->group(function() {
    Route::prefix('nav')->group(function() {
		Route::get('/', 'NavController@index')->name('site.nav');
		Route::get('data', 'NavController@data')->name('site.nav.data');
		//添加
        Route::get('create', 'NavController@create')->name('site.nav.create')->middleware('permission:site.nav.create');
        Route::post('store', 'NavController@store')->name('site.nav.store')->middleware('permission:site.nav.create');
		//编辑
        Route::get('{id}/edit', 'NavController@edit')->name('site.nav.edit')->middleware('permission:site.nav.edit');
        Route::put('{id}/update', 'NavController@update')->name('site.nav.update')->middleware('permission:site.nav.edit');
        //删除
        Route::delete('destroy', 'NavController@destroy')->name('site.nav.destroy')->middleware('permission:site.nav.destroy');
        Route::delete('restore', 'NavController@restore')->name('site.nav.restore')->middleware('permission:site.nav.destroy');
	});
});
Route::namespace('Backend\Tag')->middleware(['auth:admin','logs','permission:site.tag'])->group(function() {
    Route::prefix('tag')->group(function() {
		Route::get('/', 'TagController@index')->name('site.tag');
		Route::get('data', 'TagController@data')->name('site.tag.data');
		//添加
        Route::get('create', 'TagController@create')->name('site.tag.create')->middleware('permission:site.tag.create');
        Route::post('store', 'TagController@store')->name('site.tag.store')->middleware('permission:site.tag.create');
		//编辑
        Route::get('{id}/edit', 'TagController@edit')->name('site.tag.edit')->middleware('permission:site.tag.edit');
        Route::put('{id}/update', 'TagController@update')->name('site.tag.update')->middleware('permission:site.tag.edit');
        //删除
        Route::delete('destroy', 'TagController@destroy')->name('site.tag.destroy')->middleware('permission:site.tag.destroy');
        Route::delete('restore', 'TagController@restore')->name('site.tag.restore')->middleware('permission:site.tag.destroy');
	});
});
Route::namespace('Backend\Content')->middleware(['auth:admin','logs','permission:site.content'])->group(function() {
    Route::prefix('column')->middleware(['permission:site.column'])->group(function() {
		Route::get('/', 'ColumnController@index')->name('site.column');
		Route::get('data', 'ColumnController@data')->name('site.column.data');
		//添加
        Route::get('create', 'ColumnController@create')->name('site.column.create')->middleware('permission:site.column.create');
        Route::post('store', 'ColumnController@store')->name('site.column.store')->middleware('permission:site.column.create');
		//编辑
        Route::get('{id}/edit', 'ColumnController@edit')->name('site.column.edit')->middleware('permission:site.column.edit');
        Route::put('{id}/update', 'ColumnController@update')->name('site.column.update')->middleware('permission:site.column.edit');
        //删除
        Route::delete('destroy', 'ColumnController@destroy')->name('site.column.destroy')->middleware('permission:site.column.destroy');
        Route::delete('restore', 'ColumnController@restore')->name('site.column.restore')->middleware('permission:site.column.destroy');
	});

    Route::prefix('article')->middleware(['permission:site.article'])->group(function() {
		Route::get('/', 'ArticleController@index')->name('site.article');
		Route::get('data', 'ArticleController@data')->name('site.article.data');
		//添加
        Route::get('create', 'ArticleController@create')->name('site.article.create')->middleware('permission:site.article.create');
        Route::post('store', 'ArticleController@store')->name('site.article.store')->middleware('permission:site.article.create');
		//编辑
        Route::get('{id}/edit', 'ArticleController@edit')->name('site.article.edit')->middleware('permission:site.article.edit');
        Route::put('{id}/update', 'ArticleController@update')->name('site.article.update')->middleware('permission:site.article.edit');
        //删除
        Route::delete('destroy', 'ArticleController@destroy')->name('site.article.destroy')->middleware('permission:site.article.destroy');
	});
    Route::prefix('review')->middleware(['permission:site.review'])->group(function() {
		Route::get('/', 'ReviewController@index')->name('site.review');
		Route::get('data', 'ReviewController@data')->name('site.review.data');
		//添加
        Route::get('create', 'ReviewController@create')->name('site.review.create')->middleware('permission:site.review.create');
        Route::post('store', 'ReviewController@store')->name('site.review.store')->middleware('permission:site.review.create');
		//编辑
        Route::get('{id}/edit', 'ReviewController@edit')->name('site.review.edit')->middleware('permission:site.review.edit');
        Route::put('{id}/update', 'ReviewController@update')->name('site.review.update')->middleware('permission:site.review.edit');
        //删除
        Route::delete('destroy', 'ReviewController@destroy')->name('site.review.destroy')->middleware('permission:site.review.destroy');
	});
});
Route::namespace('Backend\Image')->middleware(['auth:admin','logs','permission:site.image'])->group(function() {
    Route::prefix('image')->group(function() {
		Route::get('/', 'ImageController@index')->name('site.image');
		Route::get('data', 'ImageController@data')->name('site.image.data');
		//添加
        Route::get('create', 'ImageController@create')->name('site.image.create')->middleware('permission:site.image.create');
        Route::post('storeimg', 'ImageController@data')->name('site.image.storeimg')->middleware('permission:site.image.create');
        Route::post('store', 'ImageController@store')->name('site.image.store')->middleware('permission:site.image.create');
		//编辑
        Route::get('{id}/edit', 'ImageController@edit')->name('site.image.edit')->middleware('permission:site.image.edit');
        Route::put('{id}/update', 'ImageController@update')->name('site.image.update')->middleware('permission:site.image.edit');
        //删除
        Route::delete('destroy', 'ImageController@destroy')->name('site.image.destroy')->middleware('permission:site.image.destroy');
        Route::delete('restore', 'ImageController@restore')->name('site.image.restore')->middleware('permission:site.image.destroy');
	});
});
Route::namespace('Backend\Banner')->middleware(['auth:admin','logs','permission:site.banner'])->group(function() {
    Route::prefix('banner')->group(function() {
		Route::get('/', 'BannerController@index')->name('site.banner');
		Route::get('data', 'BannerController@data')->name('site.banner.data');
		//添加
        Route::get('create', 'BannerController@create')->name('site.banner.create')->middleware('permission:site.banner.create');
        Route::post('store', 'BannerController@store')->name('site.banner.store')->middleware('permission:site.banner.create');
		//编辑
        Route::get('{id}/edit', 'BannerController@edit')->name('site.banner.edit')->middleware('permission:site.banner.edit');
        Route::put('{id}/update', 'BannerController@update')->name('site.banner.update')->middleware('permission:site.banner.edit');
        //删除
        Route::delete('destroy', 'BannerController@destroy')->name('site.banner.destroy')->middleware('permission:site.banner.destroy');
	});
});
Route::namespace('Backend\User')->middleware(['auth:admin','logs','permission:website.users'])->group(function() {
    Route::prefix('user')->middleware(['permission:website.user'])->group(function() {
		Route::get('/', 'UserController@index')->name('website.user');
		Route::get('data', 'UserController@data')->name('website.user.data');
		//添加
        Route::get('create', 'UserController@create')->name('website.user.create')->middleware('permission:website.user.create');
        Route::post('store', 'UserController@store')->name('website.user.store')->middleware('permission:website.user.create');
		//编辑
        Route::get('{id}/edit', 'UserController@edit')->name('website.user.edit')->middleware('permission:website.user.edit');
        Route::put('{id}/update', 'UserController@update')->name('website.user.update')->middleware('permission:website.user.edit');
        //删除
        Route::delete('destroy', 'UserController@destroy')->name('website.user.destroy')->middleware('permission:website.user.destroy');
	});
    Route::prefix('grade')->middleware(['permission:website.grade'])->group(function() {
		Route::get('/', 'GradeController@index')->name('website.grade');
		Route::get('data', 'GradeController@data')->name('website.grade.data');
		//添加
        Route::get('create', 'GradeController@create')->name('website.grade.create')->middleware('permission:website.grade.create');
        Route::post('store', 'GradeController@store')->name('website.grade.store')->middleware('permission:website.grade.create');
		//编辑
        Route::get('{id}/edit', 'GradeController@edit')->name('website.grade.edit')->middleware('permission:website.grade.edit');
        Route::put('{id}/update', 'GradeController@update')->name('website.grade.update')->middleware('permission:website.grade.edit');
        //删除
        Route::delete('destroy', 'GradeController@destroy')->name('website.grade.destroy')->middleware('permission:website.grade.destroy');
	});
    Route::prefix('point')->middleware(['permission:website.point'])->group(function() {
		Route::get('/', 'PointController@index')->name('website.point');
		Route::get('data', 'PointController@data')->name('website.point.data');
		//添加
        Route::get('create', 'PointController@create')->name('website.point.create')->middleware('permission:website.point.create');
        Route::post('store', 'PointController@store')->name('website.point.store')->middleware('permission:website.point.create');
		//编辑
        Route::get('{id}/edit', 'PointController@edit')->name('website.point.edit')->middleware('permission:website.point.edit');
        Route::put('{id}/update', 'PointController@update')->name('website.point.update')->middleware('permission:website.point.edit');
        //删除
        Route::delete('destroy', 'PointController@destroy')->name('website.point.destroy')->middleware('permission:website.point.destroy');
	});
    
    
});
Route::namespace('Backend\Website')->middleware(['auth:admin','logs','permission:website.site'])->group(function() {
    Route::prefix('site')->group(function() {
        Route::get('/', 'SiteController@index')->name('website.site');
		Route::get('data', 'SiteController@data')->name('website.site.data');
		//编辑
        Route::put('update', 'SiteController@update')->name('website.site.update')->middleware('permission:website.site.edit');

	});
});

Route::namespace('Backend\Link')->middleware(['auth:admin','logs','permission:website.link'])->group(function() {
    Route::prefix('link')->group(function() {
		Route::get('/', 'LinkController@index')->name('website.link');
		Route::get('data', 'LinkController@data')->name('website.link.data');
		//添加
        Route::get('create', 'LinkController@create')->name('website.link.create')->middleware('permission:website.link.create');
        Route::post('store', 'LinkController@store')->name('website.link.store')->middleware('permission:website.link.create');
		//编辑
        Route::get('{id}/edit', 'LinkController@edit')->name('website.link.edit')->middleware('permission:website.link.edit');
        Route::put('{id}/update', 'LinkController@update')->name('website.link.update')->middleware('permission:website.link.edit');
        //删除
        Route::delete('destroy', 'LinkController@destroy')->name('website.link.destroy')->middleware('permission:website.link.destroy');
	});
});
Route::namespace('Backend\Oauth')->middleware(['auth:admin','logs','permission:website.oauth'])->group(function() {
    Route::prefix('oauth')->group(function() {
		Route::get('/', 'OauthController@index')->name('website.oauth');
		Route::get('data', 'OauthController@data')->name('website.oauth.data');
		//添加
        Route::get('create', 'OauthController@create')->name('website.oauth.create')->middleware('permission:website.oauth.create');
        Route::post('store', 'OauthController@store')->name('website.oauth.store')->middleware('permission:website.oauth.create');
		//编辑
        Route::get('{id}/edit', 'OauthController@edit')->name('website.oauth.edit')->middleware('permission:website.oauth.edit');
        Route::put('{id}/update', 'OauthController@update')->name('website.oauth.update')->middleware('permission:website.oauth.edit');
        //删除
        Route::delete('destroy', 'OauthController@destroy')->name('website.oauth.destroy')->middleware('permission:website.oauth.destroy');
	});
});

