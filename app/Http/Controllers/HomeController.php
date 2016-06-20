<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
      $this->middleware('auth');
      $this->user = Auth::user();
    }

    public function index(){
      return view('home', ['user' => $this->user]);
    }

    public function showSettings(){
      return view('settings', ['user' => $this->user]);
    }

    public function showLogs() {
      $filename = realpath( storage_path().'/logs/laravel.log' );
      $logs     = array();
      $labels   = array(
        'INFO'  => 'info',
        'ERROR' => 'danger'
      );

      if( file_exists($filename) ){
        $lines = file($filename);
        foreach( $lines as $line ){
          $line = trim($line);
          if( preg_match( '/^\[(.+)\]\s+local\.([^\s]+):\s+(.+)$/i', $line, $m ) ){
            $text = $m[3];
            if( mb_strlen($text) > 100 ){
              $text = substr( $text, 0, 100 ) . ' ...';
            }

            $logs[] = array(
              'date'  => $m[1],
              'type'  => $m[2],
              'label' => $labels[$m[2]],
              'msg'   => $text,
              'full'  => $m[3]
            );
          }
        }
      }

      return view('logs', ['user' => $this->user, 'logs' => array_reverse($logs) ]);
    }

    public function clearLogs() {
      $filename = realpath( storage_path().'/logs/laravel.log' );
      @unlink($filename);
      return redirect("/logs");
    }
}
