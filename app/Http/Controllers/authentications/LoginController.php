<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.login', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'password' => 'required',
    ]);

    $credentials = $request->only('username', 'password');

    if (Auth::attempt($credentials)) {
      return redirect()->route('dashboard');
    }

    return redirect()->back()
      ->withInput($request->only('username'))
      ->with('error', 'Username atau Password Anda Salah!',);
  }
}
