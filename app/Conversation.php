<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    //
    protected $table = 'conversations';
    protected $fillable = [
        'sender_nopeg',
        'in_queue',
        'is_resolved',
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
