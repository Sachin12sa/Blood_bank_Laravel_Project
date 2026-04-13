<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;

use Illuminate\Contracts\Queue\ShouldQueue;

class DonationApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('✅ Donation Approved!')
            ->greeting('Congratulations!')
            ->line('Your donation request has been approved.')
            ->line('Blood unit created and certificate generated.')
            ->action('Download Certificate', route('donor.certificate.download', $this->donation))
            ->line('Thank you for saving lives!');
    }

    public function toArray($notifiable): array
    {
        return [
            'donation_id' => $this->donation->id,
            'message' => 'Donation approved! Certificate ready.',
        ];
    }
}

