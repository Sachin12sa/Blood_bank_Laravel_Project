<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;
use App\Models\Donor;

class DonationRequestNotification extends Notification
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
        $donor = $this->donation->donor;
        return (new MailMessage)
            ->subject('New Donation Request - Pending Approval')
            ->greeting('New Donation Request')
            ->line("Donor: **{$donor->user->name}** ({$donor->blood_group})")
            ->line("Date: {$this->donation->donated_at->format('M d, Y')}")
            ->action('Review & Approve', route('admin.donations.index'))
            ->line('Approve to process blood unit and generate certificate.');
    }

    public function toArray($notifiable): array
    {
        $donor = $this->donation->donor;
        return [
            'donation_id' => $this->donation->id,
            'donor_name' => $donor->user->name,
            'blood_group' => $donor->blood_group,
            'message' => 'New donation request pending approval',
        ];
    }
}

