<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        if (view()->exists('auth.forgot-password')) {
            return view('auth.forgot-password');
        }
        return redirect('/')->with('status', 'Password reset page not available.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower($request->input('email'));
        $user = User::where('email', $email)->first();

        // Create a pending request regardless of user existence (avoid leaking emails)
        PasswordResetRequest::create([
            'user_id' => $user?->id,
            'email' => $email,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return back()->with('status', 'If the email exists, your request was submitted for approval.');
    }
}


