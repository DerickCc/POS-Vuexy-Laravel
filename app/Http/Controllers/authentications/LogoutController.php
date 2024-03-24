<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function index()
    {
        return redirect()->intended('login');
    }

    public function logout()
    {
        Auth::logout();
    }
}
