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
    // app/Models/Lead.php
    public function session()
    {
        // leads.session_id stores the UUID string; ChatSession has a 'session_id' column too
        return $this->belongsTo(\App\Models\ChatSession::class, 'session_id', 'session_id');
    }
}
