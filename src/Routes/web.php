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
