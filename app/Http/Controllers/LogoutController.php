<?php

namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{

    public function logout(Request $request)
        {
            ActivityLogger::log('logout', 'User', Auth::id());

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        }
}
