<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;

class DonationRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Donation $donation)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('❌ Donation Request Rejected')
            ->greeting('Donation Update')
            ->line('Your donation request has been rejected.')
            ->line('Please check eligibility or try again later.')
            ->action('My Dashboard', route('donor.dashboard'));
    }

    public function toArray($notifiable): array
    {
        return [
            'donation_id' => $this->donation->id,
            'message' => 'Donation rejected.',
        ];
    }
}

