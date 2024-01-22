<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YourExcelExport;
use App\Imports\YourExcelImport;

use App\Models\Place;

class ExelController extends Controller
{
    public function uploadFile(Request $request)
    {
        $request->validate([
          'excelFiles.*' => 'required|mimes:xlsx,xls',
        ]);

        try {
          $files = $request->file('excelFile');

          foreach ($files as $oneExcelFile) {
              $fileName = $oneExcelFile->getClientOriginalName();
              $import = new YourExcelImport($fileName);
              Excel::import($import, $oneExcelFile);
          }


          return response()->json([
            'status' => 1,
            'message' => 'File uploaded and data inserted successfully'
          ]);
        } catch (\Exception $e) {
          return response()->json([
            'status' => 0,
            'error' => $e->getMessage(),
          ],401);
        }
    }

    public function exportFile($id,Excel $excel){
      try {
        $place = Place::find($id);
        $filename = $place->place_id . '.xlsx';
        return Excel::download(new YourExcelExport($id), $filename);

        // return response()->json([
        //   'status' => 1,
        //   'message' => 'File uploaded and data inserted successfully'
        // ]);
      } catch (\Exception $e) {
        return response()->json([
          'status' => 0,
          'error' => $e->getMessage(),
        ],401);
      }
    }
}
