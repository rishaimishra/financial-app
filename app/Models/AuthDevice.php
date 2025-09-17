<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthDevice extends Model
{
    use HasFactory;

    protected $table = 'auth_devices';

    protected $fillable = [
        'user_id',
        'device_id',
        'device_info',
    ];

    protected $casts = [
        'device_info' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}