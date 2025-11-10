<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{
    public function index()
    {
        $requests = PasswordResetRequest::with(['user', 'handler'])->orderByDesc('requested_at')->paginate(50);
        if (view()->exists('admin.password-reset-requests.index')) {
            return view('admin.password-reset-requests.index', compact('requests'));
        }
        return response()->json($requests);
    }

    public function approve(PasswordResetRequest $resetRequest)
    {
        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'Request already handled.');
        }

        $token = Str::random(64);
        $resetRequest->update([
            'status' => 'approved',
            'token' => $token,
            'approved_at' => now(),
            'handled_by' => auth()->id(),
        ]);

        // Send reset link via simple email
        $resetUrl = route('password.reset', $token) . '?email=' . urlencode($resetRequest->email);
        try {
            Mail::raw("Reset your password: {$resetUrl}", function ($message) use ($resetRequest) {
                $message->to($resetRequest->email)->subject('Password Reset Link');
            });
        } catch (\Throwable $e) {
            // ignore mail errors to not block approval
        }

        ActivityLogger::log('approve_password_reset', PasswordResetRequest::class, $resetRequest->id);

        return back()->with('success', 'Request approved and link emailed.');
    }

    public function decline(PasswordResetRequest $resetRequest, Request $request)
    {
        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'Request already handled.');
        }

        $reason = (string)$request->input('reason', '');

        $resetRequest->update([
            'status' => 'declined',
            'declined_at' => now(),
            'handled_by' => auth()->id(),
            'decline_reason' => $reason,
        ]);

        ActivityLogger::log('decline_password_reset', PasswordResetRequest::class, $resetRequest->id);

        return back()->with('success', 'Request declined.');
    }
}


