<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    public function counters() {
        return $this->hasMany(Counter::class,'worker_id');
    }

    public function wallets() {
        return $this->hasMany(Wallet::class);
    }

    public function phones() {
        return $this->hasMany(Phone::class,'worker_id');
    }

    public static function userProfit($type,$id) {

      $credit = Wallet::where('user_id', $id)
      ->where('transaction_type', 'credit')
      ->where('status', $type)
      ->get()->sum('amount');

      $debit = Wallet::where('user_id', $id)
            ->where('transaction_type', 'debit')
            ->where('status', $type)
            ->get()->sum('amount');

      $totalAmount = $credit - $debit;

      return $totalAmount;
    }

    public static function allProfit($type) {

      $credit = Wallet::where('transaction_type', 'credit')
      ->where('status', $type)
      ->get()->sum('amount');

      $debit = Wallet::where('transaction_type', 'debit')
            ->where('status', $type)
            ->get()->sum('amount');

      $totalAmount = $credit - $debit;

      return $totalAmount;
  }

}
