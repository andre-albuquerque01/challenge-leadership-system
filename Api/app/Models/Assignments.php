<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Assignments extends Model
{
    use HasUlids;

    protected $table = 'assignments';
    protected $primaryKey = "idAssignment";
    protected $fillable = [
        'idMember',
        'idLeader',
    ];

    public function userMember()
    {
        return $this->belongsTo(User::class, 'idMember');
    }
    public function userLeader()
    {
        return $this->belongsTo(User::class,'idLeader');
    }
}
