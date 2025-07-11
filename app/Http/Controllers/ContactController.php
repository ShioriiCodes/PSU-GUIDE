<?php

namespace App\Http\Controllers;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Send the email
        Mail::to('psuguide.info@gmail.com')->send(new ContactMessage($validated));

        // Log the public action (no user_id, so null)
        ActivityLogger::log('contact_message_sent', 'PublicContact', null);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }


}
