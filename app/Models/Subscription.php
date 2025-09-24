<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'api_key',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function widget()
    {
        return $this->belongsTo(\App\Models\Widget::class, 'api_key', 'api_key');
    }
}
