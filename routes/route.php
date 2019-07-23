<?php

Route::get('/qbm/callback/{connection}', ['as' => 'qbm.callback', 'uses' => 'AuthController@callback']);