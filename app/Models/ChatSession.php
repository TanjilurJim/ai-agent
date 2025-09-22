<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model {
  protected $fillable = ['api_key','widget_id','session_id','ip','user_agent'];
  public function messages(){ return $this->hasMany(ChatMessage::class); }
}
