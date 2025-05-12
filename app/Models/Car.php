<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'year',
        'car_type',
        'daily_rent_price',
        'availability',
        'image',
    ];

    protected $casts = [
        'availability' => 'boolean',
        'daily_rent_price' => 'decimal:2',
        'year' => 'integer',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function isAvailableForDates($startDate, $endDate)
    {
        if (!$this->availability) {
            return false;
        }

        return !$this->rentals()
            ->where('status', '!=', 'canceled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}
