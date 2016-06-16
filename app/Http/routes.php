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

Route::auth();

Route::group( ['prefix' => 'api/v1', 'middleware' => ['auth:api', 'jvalidator']], function () {
  Route::get( '/me', 'Api\UserController@getUserProfile');
  Route::put( '/me', 'Api\UserController@setUserProfile');

  Route::put(    '/me/key/{key}/{label}/', 'Api\UserController@updateUserKey');
  Route::delete( '/me/key/{key}',          'Api\UserController@delUserKey');
});

Route::get('/', [ 'middleware' => 'auth', 'uses' => 'HomeController@index' ] );
Route::get('/settings', [ 'middleware' => 'auth', 'uses' => 'HomeController@showSettings' ] );
