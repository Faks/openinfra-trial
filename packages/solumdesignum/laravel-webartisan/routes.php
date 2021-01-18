<?php

Route::get(
    'artisan',
    [
        'as' => 'webartisan',
        'uses' => 'SolumDeSignum\WebArtisan\WebArtisanController@index'
    ]
);

Route::post(
    'artisan/run{run?}',
    [
        'as' => 'webartisan.run',
        'uses' => 'SolumDeSignum\WebArtisan\WebArtisanController@actionRpc'
    ]
);
