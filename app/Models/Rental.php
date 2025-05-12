<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'total_cost',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateTotalCost()
    {
        $startDate = $this->start_date;
        $endDate = $this->end_date;
        $days = $startDate->diffInDays($endDate) + 1; // Include both start and end days
        return $days * $this->car->daily_rent_price;
    }

    public function canBeCanceled()
    {
        return $this->status !== 'canceled' && now()->lt($this->start_date);
    }
}
