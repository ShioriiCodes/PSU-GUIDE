<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log login activity
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logged in',
                'target_type' => '', // <-- set as empty string to avoid null DB error
                'target_id' => null,
                'timestamp' => now(),
            ]);

            return match (Auth::user()->role) {
                'admin' => redirect()->route('dashboard.admin'),
                'registrar' => redirect()->route('dashboard.registrar'),
                'usg' => redirect()->route('dashboard.usg'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
        
    }


        public function logout(Request $request)
        {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logged out',
                'target_type' => '', // MUST be an empty string, not null
                'target_id' => 0,    // You must provide a dummy value (like 0)
                'timestamp' => now(),
            ]);
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }


}
