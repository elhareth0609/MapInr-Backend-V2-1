<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker_Counter extends Model
{
    use HasFactory;

    protected $fillable = [
      'counter_id',
      'worker_id',
  ];

  public function counter()
  {
      return $this->belongsTo(Counter::class);
  }

  public function worker()
  {
      return $this->belongsTo(User::class);
  }
}
