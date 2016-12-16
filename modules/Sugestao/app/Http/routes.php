<?php

Route::group(['middleware' => ['sentry', 'jwt.auth'], 'prefix' => 'api', 'namespace' => 'Sugestao\Http\Controllers'], function () {
    Route::get('sugestoes/list', 'SugestaoController@tableList');
    Route::resource('sugestoes', 'SugestaoController');
});
