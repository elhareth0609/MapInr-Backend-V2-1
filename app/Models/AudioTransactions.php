<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioTransactions extends Model
{
    use HasFactory;

    protected $fillable = [
      'transaction_id',
      'audio'
    ];

    public function transaction()
    {
        return $this->belongsTo(Wallet::class);
    }
}
