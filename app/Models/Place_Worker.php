<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place_Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'worker_id',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class);
    }
}
