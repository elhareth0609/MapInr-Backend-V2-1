<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\YourExcelImport;
use Maatwebsite\Excel\Facades\Excel;

class ExelController extends Controller
{
    public function uploadFile(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excelFile');

        Excel::import(new YourExcelImport(), $file);

        return redirect()->back()->with('success', 'File uploaded and data inserted successfully.');
    }

    public function exportFile(Request $request){
      
    }
}
