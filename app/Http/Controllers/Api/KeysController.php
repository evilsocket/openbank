<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Validator;
use Cache;
use Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KeysController extends Controller
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

  public function index() {
    return $this->user->keys()->orderBy('balance', 'DESC')->orderBy('updated_at', 'DESC')->get();
  }

  public function create(\Illuminate\Http\Request $req){
    $keys = (array)$req->json()->all();
    if( !$keys || !is_array($keys) ){
      return $this->error( 'Invalid request.', 422 );
    }

    foreach( $keys as $key ){
      $key = (array)$key;
      if( !\App\Key::validate($key) ){
        return $this->error( 'Invalid request.', 422 );
      }
    }

    foreach( $keys as $key ){
      $key = (array)$key;
      $this->user->addKey( $key['label'], $key['value'] );
    }

    return $this->index();
  }

  public function delete($key){
    $this->user->delKey($key);
    return $this->index();
  }
}
