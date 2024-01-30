<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'counter_id',
        'worker_id',
        'place_id',
        'name',
        'latitude',
        'longitude',
        'picture',
        'note',
        'status',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
