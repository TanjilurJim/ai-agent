<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalityItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'personality_id',
        'heading',
        'body',
        'order',
    ];

    public function personality()
    {
        return $this->belongsTo(Personality::class);
    }
}
