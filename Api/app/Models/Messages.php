<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasUlids;

    protected $table = 'messages';
    protected $primaryKey = "idMessage";
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'sent_at',
    ];
}
