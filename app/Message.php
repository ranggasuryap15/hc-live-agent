<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";
    protected $fillable = ['content', 'is_read', 'conversation_id'];

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }
}
