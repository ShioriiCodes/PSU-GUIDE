<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('New Contact Form Message')
            ->html("
                <h2>New Contact Message</h2>
                <p><strong>Name:</strong> {$this->data['name']}</p>
                <p><strong>Email:</strong> {$this->data['email']}</p>
                <p><strong>Message:</strong><br>" . nl2br(e($this->data['message'])) . "</p>
            ");
    }

    public function attachments(): array
    {
        return [];
    }
}
