<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use App\Models\Company;

class InviteUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Company $company, private ?string $password = null)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $signedUrl = URL::temporarySignedRoute(
            'invites.accept',
            now()->addDays(7),
            ['company' => $this->company->id, 'email' => $notifiable->email]
        );

        $tokenNote = $this->password ? 'A temporary password is provided.' : 'If you don\'t have an account we will create one for you.';

        $mail = (new MailMessage)
            ->subject('You have been invited to ' . ($this->company->name ?? 'our platform'))
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('You have been invited to join the company "' . ($this->company->name ?? '') . '" on our platform.');

        $mail->action('Accept invitation', $signedUrl);

        if (! empty($this->password)) {
            $mail->line('A temporary password has been generated for you: ' . $this->password);
            $mail->line('After signing in you can change your password in your profile.');
        } else {
            $mail->line('If you already have an account, sign in. Otherwise use the invitation link to accept and sign in automatically.');
        }

        $mail->line('This invitation link will expire in 7 days.');

        return $mail;
    }
}
