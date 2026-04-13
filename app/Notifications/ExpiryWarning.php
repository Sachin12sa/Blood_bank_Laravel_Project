<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiryWarning extends Notification
{
    use Queueable;

    protected $expiringSoon;
    protected $expired;

    public function __construct($expiringSoon, $expired)
    {
        $this->expiringSoon = $expiringSoon;
        $this->expired = $expired;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('🩸 Blood Unit Expiry Alert')
            ->greeting('Expiry Alert!');

        if ($this->expired->isNotEmpty()) {
            $message->line("**{$this->expired->count()} units have expired** and been marked as expired.");
        }

        if ($this->expiringSoon->isNotEmpty()) {
            $message->line("**{$this->expiringSoon->count()} units are expiring within 3 days:**");
            foreach ($this->expiringSoon->take(10) as $unit) {
                $message->line("• Unit #{$unit->id} ({$unit->blood_group}) — expires {$unit->expires_at->format('M d, Y')}");
            }
        }

        $message->action('View Blood Units', url('/admin/blood-units'));

        return $message;
    }
}
