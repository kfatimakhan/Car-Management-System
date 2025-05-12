<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalConfirmation extends Notification implements ShouldQueue
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
            ->subject('Car Rental Confirmation')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your car rental has been confirmed.')
            ->line('Car: ' . $this->rental->car->name . ' ' . $this->rental->car->brand . ' ' . $this->rental->car->model)
            ->line('Rental Period: ' . $startDate . ' to ' . $endDate)
            ->line('Total Cost: $' . number_format($this->rental->total_cost, 2))
            ->line('Thank you for choosing our car rental service!')
            ->action('View Booking Details', url('/rentals/' . $this->rental->id));
    }
}
