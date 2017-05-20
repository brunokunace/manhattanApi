<?php

use Illuminate\Support\Facades\Route;

// LOGINS ROUTES
Route::get('/me',[
    'uses' => 'ApiAuthController@me'
])->middleware('jwt.auth');
Route::get('/users',[
    'uses' => 'ApiAuthController@users'
])->middleware('jwt.auth');
Route::get('/user/delete/{id}',[
    'uses' => 'ApiAuthController@delete'
])->middleware('jwt.auth');
Route::post('/authenticate',[
    'uses' => 'ApiAuthController@authenticate'
]);
Route::post('/register',[
    'uses' => 'ApiAuthController@register'
]);

// RESULTS ROTES
Route::post('/importResults', [
    'uses' => 'ResultsController@importResults'
])->middleware('jwt.auth');
Route::get('/showResults', [
    'uses' => 'ResultsController@showResults'
])->middleware('jwt.auth');
Route::get('/myResults', [
    'uses' => 'ResultsController@myResults'
])->middleware('jwt.auth');

// HISTORICAL RESULTS ROTES
Route::get('/historicalresults', [
    'uses' => 'HistoricalResultsController@historicals'
])->middleware('jwt.auth');
Route::get('/historicalresults/delete/{id}', [
    'uses' => 'HistoricalResultsController@delete'
])->middleware('jwt.auth');
