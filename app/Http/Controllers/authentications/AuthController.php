<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.login', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    $credentials = $request->only('username', 'password');
    Log::error($credentials);

    if (Auth::attempt($credentials)) {
      Log::error('masok');
      return redirect()->route('dashboard');
    }

    return redirect()->back()->with('error', 'Username atau Password Salah!');
  }
}
