<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Validator;
use Cache;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class MeController extends Controller
{
  protected function error( $message, $code ){
    $resp = array(
      'status' => $code,
      'errors' => is_array($message) ? $message : array($message)
    );
    return response()->json($resp, $code);
  }

  public function __construct(){
    $this->middleware('auth:api');
    $this->user = Auth::guard('api')->user();
  }

  public function getUserProfile(){
    $now      = time();
    $currency = $this->user->getCurrency();
    $keys     = $this->user->getKeys();
    $settings = $this->user->settings()->get();
    $price    = \App\Price::current( $currency->name );
    $trends   = \App\Price::trends( $price );
    $history  = \App\Price::history( $currency->name );
    $rates    = \App\Price::rates( $currency->name );
    $tmp      = array();

    foreach( $history as $p ){
      $tmp[] = array(
        'price' => $p->price,
        'ts'    => $p->created_at->timestamp
      );
    }

    $history = $tmp;

    $balance = [
      'btc'  => 0.0,
      'fiat' => 0.0,
      'ts'   => 0,
    ];

    foreach( $keys as $key ){
      $balance['btc'] += $key->balance;
      if( $key->updated_at->timestamp > $balance['ts'] ){
        $balance['ts'] = $key->updated_at->timestamp;
      }
    }

    $balance['fiat'] = $balance['btc'] * $price->price;

    $data = array(
      'ms' => time() - $now,
      'me' => $this->user,
      'currency' => $currency,
      'status' => [
        'balance' => $balance,
        'price' => [
          'value' => $price->price,
          'ts'    => $price->created_at->timestamp,
          'trends' => $trends
        ]
      ],
      'settings' => $settings,
      'keys' => $keys,
      'history' => $history,
      'rates' => $rates
    );

    return $data;
  }

  public function setUserProfile(\Illuminate\Http\Request $req){
    $profile = (array)$req->json()->all();
    if( !$profile || !is_array($profile) ){
      Log::error( 'Could not get profile.' );
      return $this->error( 'Invalid request.', 422 );
    }

    // Check profile data.
    if( isset($profile['me']) ){
      $me = (array)$profile['me'];
      if( isset($me['name']) ){
        $name = trim($me['name']);
        if( mb_strlen($name) > 255 || mb_strlen($name) < 1 ){
          Log::error( 'Invalid name for user.' );
          return $this->error( 'Invalid request.', 422 );
        }

        $this->user->name = $name;
        $this->user->save();
      }
    }

    // Check user settings.
    if( isset($profile['settings']) ){
      $settings = (array)$profile['settings'];
      // Check all settings first.
      foreach( $settings as $key => $value ){
        if( \App\UserSetting::isValid($key, $value) == FALSE ){
          Log::error( "Invalid setting name '$key'." );
          return $this->error( 'Invalid request.', 422 );
        }
      }

      foreach( $settings as $key => $value ){
        $this->user->setSetting( $key, $value );
      }
    }

    // Check for keys
    if( isset($profile['keys']) && is_array($profile['keys']) ){
      $keys = (array)$profile['keys'];

      foreach( $keys as $key ){
        $key = (array)$key;
        if( !\App\Key::validate($key) ){
          Log::error( 'Invalid key label or value.' );
          return $this->error( 'Invalid request.', 422 );
        }
      }

      foreach( $keys as $key ){
        $key = (array)$key;
        $this->user->addKey( $key['label'], $key['value'] );
      }
    }

    return $this->getUserProfile();
  }

  public function delUserKey($key){
    if( $this->user->delKey($key) ){
      return $this->getUserProfile();
    }
    return $this->error( 'Invalid key.', 404 );
  }

  public function updateUserKey($key, $label){
    if( $this->user->updateKey($key,$label) ){
      return $this->getUserProfile();
    }
    return $this->error( 'Invalid key.', 404 );
  }
}
