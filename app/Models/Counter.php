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
        'audio',
        'note',
        'phone',
        'status',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function myPhone() {
      return $this->hasOne(Phone::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class);
    }


    public function workers()
    {
        return $this->hasMany(Worker_Counter::class);
    }

    public function shared() {
      return $this->hasOne(Shared::class,'counter_id');
    }

}
