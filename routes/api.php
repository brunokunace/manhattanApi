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
Route::group(['prefix' => 'results', 'middleware' => ['jwt.auth','laravel-acl'], 'level' => '1234567890'], function (){
    Route::post('/import', [
        'uses' => 'ResultsController@import'
    ]);
    Route::get('/all', [
        'uses' => 'ResultsController@all'
    ]);
    Route::get('/me', [
        'uses' => 'ResultsController@me'
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