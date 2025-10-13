<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserForgotPassword;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    // show the forgot-password view
    public function create()
    {
        return view('auth.forgot-password'); // your blade
    }

    // handle submission
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL', 'psuguide.info@gmail.com'));

        // Determine if this email is an admin (several checks for safety)
        $isAdmin = false;
        if ($user) {
            if (isset($user->role) && $user->role === 'admin') {
                $isAdmin = true;
            }
            if (isset($user->is_admin) && $user->is_admin) {
                $isAdmin = true;
            }
            if ($user->email === $adminEmail) {
                $isAdmin = true;
            }
        }

        if ($isAdmin) {
            // Send normal Laravel reset link to admin (email owner)
            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withErrors(['email' => __($status)]);
        }

        // Non-admin: send notification email TO ADMIN (do not send reset link to user)
        Mail::to($adminEmail)->send(new UserForgotPassword($user));

        return back()->with('status', 'Your request has been sent to the administrator.');
    }
}
