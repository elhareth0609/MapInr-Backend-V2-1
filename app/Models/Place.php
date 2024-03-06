<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
      'place_id',
      'place_name',
      'municipality_id',
      'latitude',
      'longitude',
      'phone',
      'status'
    ];

    public function counters()
    {
        return $this->hasMany(Counter::class);
    }

    public function workers()
    {
        return $this->hasMany(Place_Worker::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

}
