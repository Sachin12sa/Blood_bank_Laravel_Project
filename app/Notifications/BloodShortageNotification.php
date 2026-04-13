<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\BloodRequest;

use Illuminate\Contracts\Queue\ShouldQueue;

class BloodShortageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $bloodGroup,
        protected BloodRequest $bloodRequest
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🆘 Urgent: Blood Request for {$this->bloodGroup}")
            ->greeting("Hello {$notifiable->user->name},")
            ->line("A hospital (**{$this->bloodRequest->hospital->hospital_name}**, located at {$this->bloodRequest->hospital->address}) has just requested **{$this->bloodGroup}** blood, but our current stock is empty.")
            ->line("As a registered donor with blood group **{$this->bloodGroup}**, your immediate donation could be life-saving.")
            ->action('Donate Now', url('/donor/dashboard'))
            ->line('Thank you for being a hero! ❤️');
    }

    public function toArray($notifiable): array
    {
        return [
            'blood_group' => $this->bloodGroup,
            'blood_request_id' => $this->bloodRequest->id,
            'message' => "Urgent need for {$this->bloodGroup} blood.",
        ];
    }
}
