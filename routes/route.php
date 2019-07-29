<?php

Route::namespace('Hotrush\QuickBooksManager\Http\Controllers')
    ->name('qbm.')
    ->prefix('qbm')
    ->group(function () {
        Route::get('redirect/{connection?}', [
            'as' => 'redirect',
            'uses' => 'AuthController@redirect'
        ]);
        Route::get('callback/{connection?}', [
            'as' => 'callback',
            'uses' => 'AuthController@callback'
        ]);
        Route::post('webhook/{connection?}', [
            'as' => 'webhook',
            'uses' => 'WebhookController@handle'
        ]);
    });