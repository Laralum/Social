<?php

Route::group([
        'middleware' => ['web', 'laralum.base'],
        'prefix'     => 'social',
        'namespace'  => 'Laralum\Social\Controllers',
        'as'         => 'laralum_public::',
    ], function () {
        Route::get('/{provider}', 'SocialController@provider')->name('social');
        Route::get('/{provider}/callback', 'SocialController@callback')->name('social.callback');
        Route::get('/{provider}/unlink', 'SocialController@unlink')->name('social.unlink');
    });

    Route::group([
            'middleware' => ['web', 'laralum.base', 'laralum.auth'],
            'prefix'     => config('laralum.settings.base_url'),
            'namespace'  => 'Laralum\Social\Controllers',
            'as'         => 'laralum::',
        ], function () {
            Route::get('/social', 'SocialController@integrations')->name('social.integrations');
            Route::post('/social/settings', 'SocialController@settings')->name('social.settings');
        });
