<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewPasswordController extends Controller
{
    public function create(string $token)
    {
        if (view()->exists('auth.reset-password')) {
            return view('auth.reset-password', ['token' => $token]);
        }
        return redirect('/')->with('status', 'Reset page not available.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $reset = PasswordResetRequest::where('token', $request->token)
            ->where('email', strtolower($request->email))
            ->where('status', 'approved')
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        $user = User::where('email', strtolower($request->email))->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate the token
        $reset->token = null;
        $reset->save();

        return redirect()->route('login')->with('status', 'Password has been reset. You can now log in.');
    }
}


