<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YourExcelExport;
use App\Exports\PlacesExport;
use App\Exports\YourUserExcelExport;

use App\Imports\YourExcelImport;

use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

use App\Models\Municipality;
use App\Models\Place;
use App\Models\User;

class ExelController extends Controller
{
  public function uploadFile(Request $request) {
      $request->validate([
        'excelFiles.*' => 'required|mimes:xlsx,xls',
        'id' => [
          'required',
          'string',
          Rule::exists('municipalities', 'id'),
      ],
          ]);

      try {
        $files = $request->file('excelFile');

        foreach ($files as $oneExcelFile) {
            $fileName = $oneExcelFile->getClientOriginalName();
            $id = $request->id;
            $import = new YourExcelImport($fileName,$id);
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

  public function exportUserFile($id,Excel $excel){
    try {
      $user = User::find($id);
      // $place = Place::where('worker_id',$id)->where('worker',$id)->first();
      $filename = $user->fullname . '.xlsx';
      // $id = 0;
      $uid = $user->id;
      return Excel::download(new YourUserExcelExport($uid), $filename);


    } catch (\Exception $e) {
      return response()->json([
        'status' => 0,
        'error' => $e->getMessage(),
      ],401);
    }
  }

  public function exportFileZip($id,Excel $excel){
    try {
      $places = Place::where('municipality_id',$id)->get();
      $municipality = Municipality::find($id);

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
      $zipFileName = $municipality->name . '.zip';
      $zipFilePath = "{$tempDir}/{$zipFileName}";

      $zip = new ZipArchive;
      $zip->open($zipFilePath, ZipArchive::CREATE);

      foreach ($excelFiles as $excelFile) {
          $zip->addFile($excelFile, basename($excelFile));
      }

      $zip->close();
      File::cleanDirectory(public_path('files'));
      $publicZipFilePath = public_path('files/' . $municipality->name . '.zip');
      File::move($zipFilePath, $publicZipFilePath);

      File::cleanDirectory(storage_path('app/temp_export'));

      return response()->json(['url' => asset('files/' . $municipality->name . '.zip')]);


    } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'error' => $e->getMessage(),
        ], 401);
    }


  }

  public function exportMunicipalitysZip(Excel $excel) {
    try {
      $municipalities = Municipality::all();

      foreach ($municipalities as $municipality) {
          // Create a directory for the municipality if it doesn't exist
          $municipalityDir = storage_path("app/temp_export/{$municipality->name}");
          if (!is_dir($municipalityDir)) {
              mkdir($municipalityDir, 0755, true);
          }

          // Get places for the current municipality
          $places = Place::where('municipality_id', $municipality->id)->get();


          foreach ($places as $place) {
            $export = new PlacesExport($place->id);
            $fileName = "{$place->place_id}.xlsx";

            // Specify the directory path based on the municipality name
            $directoryPath = "temp_export/{$municipality->name}";

            // Store the Excel file in the municipality directory
            Excel::store($export, "{$directoryPath}/{$fileName}");

            // Store the file path for adding to the zip archive later
            $excelFiles[] = storage_path("app/{$directoryPath}/{$fileName}");
          }


      }

      // Create a zip file containing all Excel files organized by municipality
      $zipFileName = 'municipalities.zip';
      $zipFilePath = storage_path("app/temp_export/{$zipFileName}");

      $zip = new ZipArchive;
      $zip->open($zipFilePath, ZipArchive::CREATE);

      // Add files to the zip archive, organizing them into folders by municipality name
      foreach ($municipalities as $municipality) {
          $municipalityDir = storage_path("app/temp_export/{$municipality->name}");
          foreach (glob("{$municipalityDir}/*") as $file) {
              $zip->addFile($file, "{$municipality->name}/" . basename($file));
          }
      }

      $zip->close();

      // Move the zip file to the public directory
      $publicZipFilePath = public_path('files/' . $zipFileName);
      File::move($zipFilePath, $publicZipFilePath);

      // Clean up temporary directories
      File::cleanDirectory(storage_path('app/temp_export'));

      return response()->json(['url' => asset('files/' . $zipFileName)]);
    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json(['error' => $e->getMessage()], 500);
    }


  }
}
