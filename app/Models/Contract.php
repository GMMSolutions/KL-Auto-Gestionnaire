<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // Contract Information
        'contract_type',
        
        // Buyer Information
        'buyer_name',
        'buyer_surname',
        'buyer_birth_date',
        'buyer_address',
        'buyer_zip',
        'buyer_city',
        'buyer_phone',
        'buyer_email',
        
        // Vehicle Information
        'vehicle_brand',
        'vehicle_type',
        'first_registration_date',
        'mileage',
        'chassis_number',
        'color',
        'plate_number',
        'has_accident',
        
        // Sale Information
        'sale_price',
        'expertise_date',
        'deposit',
        'remaining_amount',
        'payment_condition',
        'warranty',
        'warranty_amount',
    ];

    protected $casts = [
        'buyer_birth_date' => 'date',
        'first_registration_date' => 'date',
        'expertise_date' => 'date',
        'has_accident' => 'boolean',
        'sale_price' => 'decimal:2',
        'deposit' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'warranty_amount' => 'decimal:2',
    ];

    protected $dates = [
        'buyer_birth_date',
        'first_registration_date',
        'expertise_date',
        'deleted_at',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
