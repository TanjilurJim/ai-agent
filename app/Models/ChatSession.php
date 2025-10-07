<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
  protected $fillable = ['api_key', 'widget_id', 'session_id', 'ip', 'user_agent', 'name', 'mobile', 'email'];


  public function messages()
  {
    return $this->hasMany(ChatMessage::class);
  }

  public function getDisplayNameAttribute(): string
  {
    if ($this->name) {
      return $this->name;
    }
    if ($this->email) {
      return $this->email;
    }
    if ($this->mobile) {
      return $this->maskedMobile();
    }
    return 'Visitor #' . $this->id;
  }

  /** One-letter initial for avatar circle */
  public function getDisplayInitialAttribute(): string
  {
    $s = trim($this->display_name);
    // Get first grapheme safely
    $ch = function_exists('mb_substr') ? mb_substr($s, 0, 1) : substr($s, 0, 1);
    return function_exists('mb_strtoupper') ? mb_strtoupper($ch) : strtoupper($ch);
  }
  
}
