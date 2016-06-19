<?php

Route::group( ['prefix' => 'api/v1', 'middleware' => ['auth', 'api']], function () {
  // /api/v1/me* higher level API
  Route::get(    '/me',                    'Api\MeController@getUserProfile');
  Route::put(    '/me',                    'Api\MeController@setUserProfile');
  Route::put(    '/me/key/{key}/{label}/', 'Api\MeController@updateUserKey');
  Route::delete( '/me/key/{key}',          'Api\MeController@delUserKey');

  // /api/v1/keys
  Route::get(    '/keys',       'Api\KeysController@index' );
  Route::post(   '/keys',       'Api\KeysController@create' );
  Route::delete( '/keys/{key}', 'Api\KeysController@delete' );
});

Route::auth();

Route::get( '/',         [ 'middleware' => 'auth', 'uses' => 'HomeController@index' ] );
Route::get( '/settings', [ 'middleware' => 'auth', 'uses' => 'HomeController@showSettings' ] );
