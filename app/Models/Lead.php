<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = ['api_key', 'widget_id', 'session_id', 'name', 'mobile', 'email'];
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}
