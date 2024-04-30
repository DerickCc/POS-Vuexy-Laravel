<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    if (Auth::attempt($credentials) && Auth::user()->account_status) {
      return redirect()->route('dashboard');
    }

    $errorMessage = Auth::user()->account_status ? 'Username atau Password Anda Salah!' : 'Tidak bisa login karena akun Anda telah dinon-aktifkan!';

    return redirect()->back()
      ->withInput($request->only('username'))
      ->with('error',  $errorMessage,);
  }
}
