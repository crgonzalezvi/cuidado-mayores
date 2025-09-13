<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmergenciaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $contact;

    public function __construct($user, $contact)
    {
        $this->user = $user;
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->subject("🚨 Emergencia - {$this->user->name}")
            ->view('emails.emergencia');
    }
}
