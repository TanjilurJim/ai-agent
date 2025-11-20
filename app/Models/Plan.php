<?php

// app/Models/Plan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'widget_limit',
        'personality_limit',
        'daily_prompt_limit',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
