<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'avatar',
        'name',
        'widgetName',
        'welcomeMessage',
        'color',
        'user_id',
        'api_key',
        'is_active',
        'personality_id',
    ];

    protected $appends = ['avatar_url'];

    protected static function booted()
    {
        static::creating(function ($w) {
            if (empty($w->api_key)) $w->api_key = Str::random(20);
            if (empty($w->user_id)) $w->user_id = auth()->id();
        });
    }

    public function personalities()
    {
        return $this->belongsToMany(Personality::class, 'personality_widget')
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('personality_widget.order');
    }

    public function getAvatarUrlAttribute(): string
    {
        $path = $this->avatar;

        if (!$path) {
            return asset('assets/images/upload-placeholder.jpg');
        }

        // If it's already absolute, just return it
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Make relative paths absolute using APP_URL
        return asset(ltrim($path, '/'));
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMine($q)
    {
        return $q->where('user_id', auth()->id());
    }
}
