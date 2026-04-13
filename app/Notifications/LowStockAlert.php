<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    protected $lowGroups;

    public function __construct($lowGroups)
    {
        $this->lowGroups = $lowGroups;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('⚠️ Blood Bank Low Stock Alert')
            ->greeting('Low Stock Warning!')
            ->line('The following blood groups are below the minimum threshold:');

        foreach ($this->lowGroups as $inv) {
            $message->line("**{$inv->blood_group}**: {$inv->available_units} available (threshold: {$inv->threshold})");
        }

        $message->line('Please take action to replenish the stock.')
                ->action('View Dashboard', url('/admin/dashboard'));

        return $message;
    }
}
