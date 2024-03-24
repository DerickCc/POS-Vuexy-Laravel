<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.register', ['pageConfigs' => $pageConfigs]);
  }

  public function register(Request $request)
  {
    $user = User::create([
      'username' => $request['username'],
      'name' => $request['name'],
      'password' => bcrypt($request['password']),
      'role' => 'superadmin'
    ]);

    if (!$user) {
      abort(500);
    }

    return redirect()->intended('login')->with('success', 'User Berhasil Dibuat!');
  }
}
