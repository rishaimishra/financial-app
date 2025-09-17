<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UdhariReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'udhari_id',
        'reminder_message',
        'channel',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function udhariRecord()
    {
        return $this->belongsTo(UdhariRecord::class, 'udhari_id');
    }
}