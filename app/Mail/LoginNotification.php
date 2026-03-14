<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Personnel;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $personnel;
    // now keep plain password (requested) -- WARNING: insecure in production
    public $plainPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(Personnel $personnel, string $plainPassword)
    {
        $this->personnel = $personnel;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Connexion réussie')
                    ->view('emails.auth.login');
    }
}
