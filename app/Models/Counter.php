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

    protected static function booted() {
        static::saved(function ($counter) {
            if ($counter->place) {
                $counter->place->touch(); // Update the updated_at timestamp
            }
        });
    }

    public function place() {
        return $this->belongsTo(Place::class);
    }

    public function myPhone() {
      return $this->hasOne(Phone::class);
    }

    public function worker() {
        return $this->belongsTo(User::class);
    }


    public function workers() {
        return $this->hasMany(Worker_Counter::class);
    }

    public function shared() {
      return $this->hasOne(Shared::class,'counter_id');
    }

    public function bills() {
        return $this->hasMany(Bill::class);
    }
}
