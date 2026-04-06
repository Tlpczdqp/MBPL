<?php
// This file MUST exist at app/Mail/OtpMail.php
// The folder is app/Mail/ — create it if it doesn't exist
// The "use App\Mail\OtpMail" in the controller points to THIS file

namespace App\Mail;

// These imports tell PHP where to find the parent classes
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// OtpMail MUST extend Mailable
// That is what makes it a valid mail class
// Without "extends Mailable" → PHP throws the type error you saw
class OtpMail extends Mailable
{
    // These two traits are standard for all Mailables
    // Queueable  → allows this mail to be queued (sent in background)
    // SerializesModels → safely serializes Eloquent models in queues
    use Queueable, SerializesModels;

    // We store the user and OTP as public properties
    // Public properties are automatically available inside the blade view
    // So in resources/views/emails/otp.blade.php you can use $user and $otp
    public User $user;
    public string $otp;

    // The constructor receives the data from wherever the mail is sent
    // Example: Mail::to($user->email)->send(new OtpMail($user, $otp))
    public function __construct(User $user, string $otp)
    {
        // Assign to public properties so the view can access them
        $this->user = $user;
        $this->otp  = $otp;
    }

    // envelope() defines the email subject and other headers
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your OTP Verification Code — Business Permit System',
        );
    }

    // content() defines WHICH blade view renders the email body
    public function content(): Content
    {
        return new Content(
            // This points to resources/views/emails/otp.blade.php
            view: 'emails.otp',
        );
    }

    // attachments() defines files attached to the email
    // We don't attach anything, so return an empty array
    public function attachments(): array
    {
        return [];
    }
}