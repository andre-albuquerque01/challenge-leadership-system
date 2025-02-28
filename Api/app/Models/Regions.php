<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    use HasUlids;

    protected $table = 'regions';
    protected $primaryKey = "idRegion";
    protected $fillable = [
        'cep',
        'address',
        'house',
        'neighborhood',
        'userId',
    ];

    public function user()
    {
        return $this->belongsTo (User::class, 'userId');
    }
}
