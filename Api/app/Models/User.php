<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasUlids;

    protected $primaryKey = 'idUser';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'term_aceite',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function region()
    {
        return $this->hasMany(Regions::class, 'userId');
    }

    public function assignmentsMember()
    {
        return $this->hasMany(Assignments::class, 'idMember', 'idUser');
    }
    public function assignmentsLeader()
    {
        return $this->hasMany(Assignments::class, 'idLeader', 'idUser');
    }
    public function senderMessage()
    {
        return $this->hasMany(Messages::class, 'sender_id', 'idUser');
    }
    public function receiverMessage()
    {
        return $this->hasMany(Messages::class, 'receiver_id', 'idUser');
    }
}
