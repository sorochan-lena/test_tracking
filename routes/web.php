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

Route::get('/', 'ClickController@index')->name('index');

Route::get('click', 'ClickController@click')->name('click');

Route::get('link', 'ClickController@link')->name('link');

Route::get('success/{id}', 'ClickController@success')->where('id', '[A-z0-9-]+')
    ->name('success');

Route::get('error/{id}', 'ClickController@error')->where('id', '[A-z0-9-]+')
    ->name('error');

Route::get('bad-domains', 'BadDomainController@index')->name('bad-domains.index');

Route::post('bad-domains', 'BadDomainController@store')->name('bad-domains.store');

