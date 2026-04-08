<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    // Public properties are automatically available in the blade view
    public User $user;
    public string $resetUrl;

    // Constructor receives the user and the reset URL
    // Called like: new PasswordResetMail($user, $resetUrl)
    public function __construct(User $user, string $resetUrl)
    {
        $this->user     = $user;
        $this->resetUrl = $resetUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Password — Business Permit System',
        );
    }

    public function content(): Content
    {
        return new Content(
            // Points to resources/views/emails/password-reset.blade.php
            view: 'emails.password-reset',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}