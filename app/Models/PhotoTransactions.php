<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoTransactions extends Model
{
    use HasFactory;

    protected $fillable = [
      'transaction_id',
      'photo'
    ];

    public function transaction() {
        return $this->belongsTo(Wallet::class);
    }

    public function photoUrl() {
        return asset('storage/assets/img/wallets/' . $this->photo);
    }


}
