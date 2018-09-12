<?php

Route::group([

    'prefix' => 'auth'

], function () {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group(['middleware' => 'jwt'], function () {
    // search goods... via POST (may be GET)
    Route::post('search', 'APIGoodController@apiSearch');

    // resource, goods crud
    Route::resource('good', 'APIGoodController')
        ->only(['index', 'show', 'store', 'update' ,'destroy']);
});
