<?php

namespace App\Exports;

use App\Models\Counter;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class YourExcelTranactionsExport implements FromCollection, WithEvents
{
  protected $type;

    public function __construct($type)
    {
      $this->type = $type;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
      if ($this->type == 'Tranactions') {
        $tranactions = Wallet::all();

        $data = $tranactions->map(function ($tranaction) {
          return [
            'Id' => $tranaction->id,
            'User Id' => $tranaction->user_id,
            'Amount' => $tranaction->amount,
            'Transaction Type' => $tranaction->transaction_type,
            'Status' => $tranaction->status,
            'Description' => $tranaction->description,
            'Created At' => $tranaction->created_at,
            'Updated At' => $tranaction->updated_at
          ];
        });

        $headers = [
          'Id', 'User Id', 'Amount','Transaction Type', 'Status', 'Description', 'Created At', 'Updated At'
        ];

      } else if ($this->type == 'Users') {
        $users = User::all();

        // Transform counters into a collection
        $data = $users->map(function ($user) {
          return [
            'Id' => $user->id,
            'Full Name' => $user->fullname,
            'Email' => $user->email,
            'Phone' => $user->phone,
            'Password' => $user->password,
            'Role' => $user->role,
            'Remember Token' => $user->remember_token,
            'Created At' => $user->created_at,
            'Updated At' => $user->updated_at
          ];
        });

        // Add headers to the collection
        $headers = [
          'Id', 'Full Name','Email', 'Phone', 'Password', 'Role', 'Remember Token', 'Created At', 'Updated At'
        ];
      }

        // Prepend headers to the data collection
        $collection = collect([$headers])->merge($data);

        return $collection;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

            },
        ];
    }



  }
