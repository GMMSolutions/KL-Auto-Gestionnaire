<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'chassis_number',
        'vehicle_brand',
        'vehicle_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to search for a vehicle by VIN.
     */
    public function scopeSearchByVin($query, $vin)
    {
        return $query->where('chassis_number', $vin);
    }

    /**
     * Get all repairs for the vehicle.
     */
    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }
}
