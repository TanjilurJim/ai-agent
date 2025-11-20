<?php

// app/Models/UserDailyUsage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDailyUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'prompt_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
