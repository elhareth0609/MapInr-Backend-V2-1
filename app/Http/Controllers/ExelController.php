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
use Illuminate\Support\Facades\File;

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
            'state' => __('Success'),
            'message' => __('Files Upload It Successfully')
          ]);
        } catch (\Exception $e) {
          return response()->json([
            'status' => 0,
            'errors' => $e->getMessage(),
          ],401);
        }
    }

    public function exportFile($id,Excel $excel){
      try {
        $place = Place::where('place_id',$id)->first();
        $filename = $id . '.xlsx';
        $id = $place->id;
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
        $places = Place::all();

        // Create a temporary directory to store individual Excel files
        $tempDir = storage_path('app/temp_export');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Create Excel files for each place and store them in the temporary directory
        $excelFiles = [];
        foreach ($places as $place) {
            $export = new PlacesExport($place->id);
            $fileName = "{$place->place_id}.xlsx";
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
        File::cleanDirectory(public_path('files'));
        $publicZipFilePath = public_path('files/file.zip');
        File::move($zipFilePath, $publicZipFilePath);

        File::cleanDirectory(storage_path('app/temp_export'));

        return response()->json(['url' => asset('files/file.zip')]);


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
            unlink($zipFilePath);
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
