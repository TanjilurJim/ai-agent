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

    protected static function booted()
    {
        static::creating(function ($w) {
            if (empty($w->api_key)) $w->api_key = Str::random(20);
            if (empty($w->user_id)) $w->user_id = auth()->id();
        });
    }

    public function personality()
    {
        return $this->belongsTo(Personality::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        $path = $this->avatar;

        if (!$path) {
            return asset('assets/images/upload-placeholder.jpg');
        }

        // If DB still has old absolute URLs, just return them.
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Normalize leading slash or plain relative path -> absolute URL
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
