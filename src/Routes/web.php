<?php

Route::group([
        'middleware' => ['web', 'laralum.base'],
        'prefix'     => 'social',
        'namespace'  => 'Laralum\Social\Controllers',
        'as'         => 'laralum_public::',
    ], function () {
        Route::get('/{provider}', 'SocialController@provider')->name('social');
        Route::get('/{provider}/callback', 'SocialController@callback')->name('social.callback');
    });

    Route::group([
            'middleware' => ['web', 'laralum.base', 'laralum.auth'],
            'prefix'     => config('laralum.settings.base_url'),
            'namespace'  => 'Laralum\Social\Controllers',
            'as'         => 'laralum::',
        ], function () {
            Route::post('/settings', 'SocialController@settings')->name('social.settings');
        });
