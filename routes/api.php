<?php

Route::group([
    'namespace' => 'Api',
], function() {
    Route::group([
    ], function() {
        Route::get('authorizations/test', 'AuthorizationsController@test')
            ->name('api.authorizations.test');

        // 小程序登录
        Route::post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');
        // 通用注册
        Route::post('authorizations/register', 'AuthorizationsController@register')
            ->name('api.authorizations.register');
        // 通用登录
        Route::post('authorizations/login', 'AuthorizationsController@login')
            ->name('api.authorizations.login');

        // 刷新token
        Route::post('authorizations/update', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        // 删除token
        Route::post('authorizations/destroy', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');

    });

    Route::group([
    ], function () {
        // 游客可以访问的接口
        Route::post('images/upload', 'ImagesController@upload')->name('api.images.upload');

        // 需要 token 验证的接口
        Route::group(['middleware' => 'auth:api'], function($api) {

            // 当前登录用户信息
            Route::get('user', 'UsersController@me')
                ->name('api.user.me');




        });
    });

});

