<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'namespace'  => 'Audio'
], function () {
    Route::get('audioRegister', 'RegisterController@showRegistrationForm')->name('audioRegister.index');
    Route::post('audioRegister', 'RegisterController@register')->name('audioRegister.register');
});

Auth::routes();

Route::group([
    'middleware' => ['auth'],
    'prefix'     => 'admin',
    'as'         => 'admin.',
    'namespace'  => 'Audio'
], function () {
    Route::resource('audio', 'AudioController', ['only' => ['index', 'store']]);
    Route::resource('listConvert', 'ListConvertController');
    Route::delete('listConvert/{id}/destroy', [
        'uses' => 'ListConvertController@destroy',
        'as' => 'listConvert.destroy',
    ]);
});