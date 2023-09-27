<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    //
    protected $table = 'conversations';
    protected $fillable = [
        'conversation_id',
        'sender_nopeg',
        'admin',
        'in_queue',
        'is_resolved',
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
