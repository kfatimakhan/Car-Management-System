<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminRentalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rental;

    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $startDate = $this->rental->start_date->format('M d, Y');
        $endDate = $this->rental->end_date->format('M d, Y');

        return (new MailMessage)
            ->subject('New Car Rental')
            ->greeting('Hello Admin!')
            ->line('A new car rental has been made.')
            ->line('Customer: ' . $this->rental->user->name . ' (' . $this->rental->user->email . ')')
            ->line('Car: ' . $this->rental->car->name . ' ' . $this->rental->car->brand . ' ' . $this->rental->car->model)
            ->line('Rental Period: ' . $startDate . ' to ' . $endDate)
            ->line('Total Cost: $' . number_format($this->rental->total_cost, 2))
            ->action('View Rental Details', url('/admin/rentals/' . $this->rental->id));
    }
}
