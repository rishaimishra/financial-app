<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalProgress extends Model
{
    use HasFactory;

    protected $table = 'goal_progress';

    protected $fillable = [
        'goal_id',
        'transaction_id',
        'amount',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    // Relationships
    public function goal()
    {
        return $this->belongsTo(FinancialGoal::class, 'goal_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}