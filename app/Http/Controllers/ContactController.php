<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $to = config('mail.from.address') ?: env('MAIL_TO_ADDRESS', null);

        try {
            if ($to) {
                Mail::raw(
                    "From: {$validated['name']} <{$validated['email']}>\n\n{$validated['message']}",
                    function ($m) use ($to) {
                        $m->to($to)->subject('New Contact Message - PSU-GUIDE');
                    }
                );
            }
        } catch (\Throwable $e) {
            // Ignore mail transport errors for now to not block UX
        }

        ActivityLogger::log('contact_message', null, null);

        return back()->with('success', 'Thanks! Your message has been sent.');
    }
}


