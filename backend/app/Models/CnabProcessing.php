<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CnabProcessing extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'fund',
        'sequence',
        'status',
        'cnab_path',
        'error_message'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 