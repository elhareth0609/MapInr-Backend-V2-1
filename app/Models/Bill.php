<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
      'counter_id',
      'user_id',
      'amount',
    ];


    public function counter() {
        return $this->belongsTo(Counter::class);
    }

    public function user() {
      return $this->belongsTo(User::class);
    }

}
