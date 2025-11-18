<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class ChatAttachment extends Model {
    protected $fillable = ['chat_message_id','disk','path','url','mime','size','width','height'];
    public function message(){ return $this->belongsTo(ChatMessage::class,'chat_message_id'); }
}
