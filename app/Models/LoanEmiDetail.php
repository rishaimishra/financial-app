<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanEmiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lender_name',
        'loan_amount',
        'emi_amount',
        'outstanding_balance',
        'emi_day',
        'is_auto_detected',
        'account_number',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'emi_amount' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'is_auto_detected' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reminders()
    {
        return $this->hasMany(EmiReminder::class, 'loan_emi_id');
    }
}