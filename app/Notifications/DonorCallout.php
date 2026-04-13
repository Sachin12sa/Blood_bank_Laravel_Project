<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonorCallout extends Notification
{
    use Queueable;

    protected string $bloodGroup;
    protected int $currentStock;
    protected int $threshold;

    public function __construct(string $bloodGroup, int $currentStock, int $threshold)
    {
        $this->bloodGroup = $bloodGroup;
        $this->currentStock = $currentStock;
        $this->threshold = $threshold;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🆘 Urgent: {$this->bloodGroup} Blood Needed!")
            ->greeting("Hello {$notifiable->name},")
            ->line("We urgently need **{$this->bloodGroup}** blood donors.")
            ->line("Current stock is critically low at **{$this->currentStock} units** (minimum threshold: {$this->threshold}).")
            ->line("As a registered donor with blood group **{$this->bloodGroup}**, your donation can save lives!")
            ->action('Donate Now', url('/donor/dashboard'))
            ->line('Thank you for being a life-saver. ❤️');
    }
}
