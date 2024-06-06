<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class YourExcelTranactionsImport implements ToCollection
{
    protected $fileName;

    public function __construct($fileName) {
        $this->fileName = $fileName;
    }

    public function collection(Collection $rows)
    {
      if (strpos($this->fileName, 'Tranactions') !== false) {

        $wallets = Wallet::all();
        foreach ($wallets as $wallet) {
          $wallet->delete();
        }

        $rows->skip(1)->each(function ($row) {
          $counter = new Wallet;
          $counter->id = $row[0];
          $counter->user_id = $row[1];
          $counter->amount = $row[2];
          $counter->transaction_type = $row[3];
          $counter->status = $row[4];
          $counter->description = $row[5];
          $counter->created_at = $row[6];
          $counter->updated_at = $row[7];
          $counter->save();
        });
      } else if (strpos($this->fileName, 'Users') !== false) {
        $users = User::all();
        foreach ($users as $user) {
          $user->delete();
        }

        $rows->skip(1)->each(function ($row) {
          $user = new  User;
          $user->id = $row[0];
          $user->fullname = $row[1];
          $user->email = $row[2];
          $user->phone = $row[3];
          $user->password = $row[4];
          $user->role = $row[5];
          $user->remember_token = $row[6];
          $user->created_at = $row[7];
          $user->updated_at = $row[8];
          $user->save();
        });
      }
      }
    }
