<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group([
    'prefix' => 'es'
], function () {
    Route::get('/get', 'TestController@get');
    Route::post('/add', 'TestController@add');
    Route::post('/update', 'TestController@update');
});

Route::group([
    'prefix' => 'crawler',
    'namespace'=>'\Crawler'
], function () {
    Route::get('/executeEyesCrawler', 'CrawlerController@executeEyesCrawler');
});

