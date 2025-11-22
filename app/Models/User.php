<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Plan;
use App\Models\Personality;
use App\Models\UserDailyUsage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'plan_started_at'   => 'datetime',
        'plan_expires_at'   => 'datetime',
    ];

    public function isAdmin()
    {
        return strtolower($this->role ?? '') === 'admin';
    }

    public function widgets()
    {
        return $this->hasMany(\App\Models\Widget::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function personalities()
    {
        return $this->hasMany(Personality::class);
    }
    protected function effectivePlan(): ?Plan
    {
        // Admins: ignore expiry, they are unlimited anyway
        if ($this->isAdmin()) {
            return $this->plan ?? Plan::where('name', 'free')->first();
        }

        // If no plan set → treat as free
        if (! $this->plan) {
            return Plan::where('name', 'free')->first();
        }

        // If plan has an expiry and it's in the past → treat as free
        if ($this->plan_expires_at && now()->greaterThan($this->plan_expires_at)) {
            return Plan::where('name', 'free')->first();
        }

        // Otherwise use assigned plan
        return $this->plan;
    }
    public function isPlanExpired(): bool
    {
        if ($this->isAdmin()) {
            return false;
        }

        if (! $this->plan_expires_at) {
            return false;
        }

        return now()->greaterThan($this->plan_expires_at);
    }


    public function widgetLimit(): int
    {
        if ($this->isAdmin()) {
            return PHP_INT_MAX; // effectively unlimited
        }

        return optional($this->effectivePlan())->widget_limit ?? 0;
    }

    public function personalityLimit(): int
    {
        if ($this->isAdmin()) {
            return PHP_INT_MAX;
        }

        return optional($this->effectivePlan())->personality_limit ?? 0;
    }

    public function dailyPromptLimit(): int
    {
        if ($this->isAdmin()) {
            return PHP_INT_MAX;
        }

        return optional($this->effectivePlan())->daily_prompt_limit ?? 0;
    }

    public function canCreateMoreWidgets(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->widgets()->count() < $this->widgetLimit();
    }

    public function canCreateMorePersonalities(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->personalities()->count() < $this->personalityLimit();
    }
}
