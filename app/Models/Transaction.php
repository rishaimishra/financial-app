<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'category_id',
        'type_id',
        'amount',
        'description',
        'currency',
        'source',
        'meta_data',
        'transaction_date',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class, 'type_id');
    }

    public function goalProgress()
    {
        return $this->hasOne(GoalProgress::class);
    }

    public function businessIncome()
    {
        return $this->hasOne(BusinessIncome::class);
    }

    public function businessExpense()
    {
        return $this->hasOne(BusinessExpense::class);
    }
}