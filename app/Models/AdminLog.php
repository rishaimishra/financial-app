<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'admin_logs';

    protected $fillable = [
        'admin_id',
        'action',
        'details',
        'performed_at',
    ];

    protected $casts = [
        'details' => 'array',
        'performed_at' => 'datetime',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'admin_id');
    }
}