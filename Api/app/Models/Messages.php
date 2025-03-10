<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Messages extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'messages';
    protected $primaryKey = "idMessage";
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
    ];

    public function senderMessage()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiverMessage()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
