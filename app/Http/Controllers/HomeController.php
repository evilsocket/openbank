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
}
