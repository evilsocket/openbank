<?php

Route::group( ['prefix' => 'api/v1', 'middleware' => ['auth:api']], function () {
  // /api/v1/me* higher level API
  Route::get(    '/me',                    'Api\MeController@getUserProfile');
  Route::put(    '/me',                    'Api\MeController@setUserProfile');
  Route::put(    '/me/key/{key}/{label}/', 'Api\MeController@updateUserKey');
  Route::delete( '/me/key/{key}',          'Api\MeController@delUserKey');


});

Route::auth();

Route::get( '/',         [ 'middleware' => 'auth', 'uses' => 'HomeController@index' ] );
Route::get( '/settings', [ 'middleware' => 'auth', 'uses' => 'HomeController@showSettings' ] );
