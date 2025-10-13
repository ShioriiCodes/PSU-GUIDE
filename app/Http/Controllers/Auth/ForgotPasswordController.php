<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPasswordResetRequest;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Show forgot password form
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        $adminEmail = 'psuguide.info@gmail.com'; // Or config

        if ($user && $user->role === 'admin') {
            // For admins, send reset link to their email
            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withErrors(['email' => __($status)]);
        }

        // For other users, send notification to admin
        if ($user) {
            Mail::to($adminEmail)->send(new AdminPasswordResetRequest($user));
        }

        return back()->with('status', 'Your request has been sent to the administrator.');
    }
}
