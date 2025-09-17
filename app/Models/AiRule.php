<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRule extends Model
{
    use HasFactory;

    protected $table = 'ai_rules';

    protected $fillable = [
        'name',
        'description',
        'rule_condition',
        'suggestion_template',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'rule_condition' => 'array',
        'suggestion_template' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }
}