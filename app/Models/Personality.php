<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personality extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function items()
    {
        return $this->hasMany(PersonalityItem::class)->orderBy('order');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function widgets()
    {
        return $this->belongsToMany(Widget::class, 'personality_widget')
            ->withPivot('order')
            ->withTimestamps();
    }
}
