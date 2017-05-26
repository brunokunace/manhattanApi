<?php

use Illuminate\Support\Facades\Route;

// LOGINS ROUTES
Route::group( ['prefix' => 'users'],  function (){

    Route::post('/authenticate',[
        'uses' => 'ApiAuthController@authenticate'
    ]);
    Route::post('/register',[
        'uses' => 'ApiAuthController@register'
    ]);
    Route::group(['middleware' => 'jwt.auth'], function (){
        Route::get('/me',[
            'uses' => 'ApiAuthController@me'
        ]);
        Route::get('/all',[
            'uses' => 'ApiAuthController@all'
        ]);
        Route::delete('/delete/{id}',[
            'uses' => 'ApiAuthController@delete'
        ]);
        Route::put('/update',[
            'uses' => 'ApiAuthController@update'
        ]);
        Route::put('/updatePassword',[
            'uses' => 'ApiAuthController@updatePassword'
        ]);
    });

});

// RESULTS ROUTES
Route::group(['prefix' => 'results'], function (){
    Route::post('/import', [
        'uses' => 'ResultsController@import'
    ]);
    Route::post('/porativo', [
        'uses' => 'ResultsController@porAtivo'
    ]);
    Route::post('/poracao', [
        'uses' => 'ResultsController@porAcao'
    ]);
    Route::get('/subtitular', [
        'uses' => 'ResultsController@subtitular'
    ]);
});

// HISTORICAL RESULTS ROUTES
Route::group(['prefix' => 'historical', 'middleware' => 'jwt.auth'], function (){
    Route::get('/all', [
        'uses' => 'HistoricalResultsController@all'
    ]);
    Route::delete('/delete/{id}', [
        'uses' => 'HistoricalResultsController@delete'
    ]);
});

// RATES TYPES ROUTES
Route::group(['prefix' => 'ratetypes'], function (){
    Route::get('/all', [
        'uses' => 'RateTypesController@all'
    ]);
    Route::post('', [
        'uses' => 'RateTypesController@create'
    ]);
    Route::put('', [
        'uses' => 'RateTypesController@update'
    ]);
});

