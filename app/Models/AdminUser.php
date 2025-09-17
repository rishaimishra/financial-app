<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'password_hash',
        'role',
    ];

    // Relationships
    public function aiRules()
    {
        return $this->hasMany(AiRule::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }
}