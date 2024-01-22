<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YourExcelExport;
use App\Imports\YourExcelImport;
use App\Exports\PlacesExport;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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


      } catch (\Exception $e) {
        return response()->json([
          'status' => 0,
          'error' => $e->getMessage(),
        ],401);
      }
    }

    public function exportFileZip(Excel $excel){
      try {
        $placeIds = Place::pluck('place_id');

        // Create a temporary directory to store individual Excel files
        $tempDir = storage_path('app/temp_export');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Create Excel files for each place and store them in the temporary directory
        $excelFiles = [];
        foreach ($placeIds as $placeId) {
            $export = new PlacesExport($placeId);
            $fileName = "file{$placeId}.xlsx";
            $filePath = "{$tempDir}/{$fileName}";

            Excel::store($export, $fileName, 'temp_export');

            $excelFiles[] = $filePath;
        }

        // Create a zip file containing all Excel files
        $zipFileName = 'file.zip';
        $zipFilePath = "{$tempDir}/{$zipFileName}";

        $zip = new ZipArchive;
        $zip->open($zipFilePath, ZipArchive::CREATE);

        foreach ($excelFiles as $excelFile) {
            $zip->addFile($excelFile, basename($excelFile));
        }

        $zip->close();

        // Stream the ZIP file contents directly to the response
        $response = response()->stream(
            function () use ($zipFilePath) {
                readfile($zipFilePath);
            },
            200,
            [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="file.zip"',
            ]
        );

        // Register an "after" callback to delete the temporary files
        $response->send(function () use ($excelFiles, $zipFilePath) {
            foreach ($excelFiles as $excelFile) {
                unlink($excelFile);
            }
            // Move the deletion of the ZIP file here
            Storage::disk('temp_export')->delete($zipFilePath);
        });

        return $response;


    } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'error' => $e->getMessage(),
        ], 401);
    }


    }
}
