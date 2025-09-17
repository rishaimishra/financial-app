<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsurancePolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_name',
        'policy_type',
        'policy_number',
        'premium_amount',
        'premium_frequency',
        'sum_assured',
        'maturity_date',
        'meta_data',
    ];

    protected $casts = [
        'premium_amount' => 'decimal:2',
        'sum_assured' => 'decimal:2',
        'maturity_date' => 'date',
        'meta_data' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}