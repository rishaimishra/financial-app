<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmiReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_emi_id',
        'reminder_date',
        'status',
    ];

    protected $casts = [
        'reminder_date' => 'date',
    ];

    // Relationships
    public function loanEmi()
    {
        return $this->belongsTo(LoanEmiDetail::class, 'loan_emi_id');
    }
}