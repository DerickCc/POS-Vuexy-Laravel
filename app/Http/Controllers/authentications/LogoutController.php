<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function index()
    {
        Log::error(Auth::user());
        return redirect()->intended('login');
    }

    public function logout()
    {
        Auth::guard('user')->logout();
    }
}
