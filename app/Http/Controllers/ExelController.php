<?php

namespace App\Http\Controllers;

use App\Exports\PlacesExport;
use App\Exports\YourExcelExport;
use App\Exports\YourExcelTranactionsExport;
use App\Exports\YourUserExcelExport;
use App\Imports\YourExcelImport;

use App\Imports\YourExcelTranactionsImport;

use App\Models\Municipality;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

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

  public function uploadFileTranactions(Request $request) {
    $request->validate([
      'zipFile' => 'required|mimes:zip',
    ]);

    try {
      $zip = new ZipArchive;
      $zipFilePath = request()->file('zipFile')->getPathname();
      $tempDir = storage_path('app/temp_export');
      if (!is_dir($tempDir)) {
        mkdir($tempDir, 0755, true);
      }
      $zip->open($zipFilePath);
      $zip->extractTo($tempDir);
      $zip->close();

      // Import each Excel file within the extracted directory
      $files = scandir($tempDir);
      $usersImported = false;
      foreach ($files as $file) {
          if ($file !== '.' && $file !== '..') {
              // Check if the file is for users
              if (strpos($file, 'Users') !== false) {
                  // Import users
                  Excel::import(new YourExcelTranactionsImport($file), $tempDir . '/' . $file);
                  $usersImported = true;
              }
          }
      }

      // If users are successfully imported, import transactions
      if ($usersImported) {
          foreach ($files as $file) {
              if ($file !== '.' && $file !== '..') {
                  // Check if the file is for transactions
                  if (strpos($file, 'Tranactions') !== false) {
                      // Import transactions
                      Excel::import(new YourExcelTranactionsImport($file), $tempDir . '/' . $file);
                  }
              }
          }
      } else {
          // Handle the case where users are not imported before transactions
          return response()->json([
              'status' => 'Error',
              'message' => 'Users must be imported before transactions.'
          ], 400);
      }


      // Delete the extracted files
      foreach ($files as $file) {
          if ($file !== '.' && $file !== '..') {
              unlink($tempDir . '/' . $file);
          }
      }

      // Delete the extracted directory
      // rmdir($tempDir);

          // $import = new YourExcelTranactionsImport();
          // Excel::import($import, $request->file('excelFile'));

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

  public function exportFileTranactions(Excel $excel){
    try {

      // Create a temporary directory to store individual Excel files
      $tempDir = storage_path('app/temp_export');
      if (!is_dir($tempDir)) {
          mkdir($tempDir, 0755, true);
      }

      // Create Excel files for each place and store them in the temporary directory
      $excelFiles = [];

      $export = new YourExcelTranactionsExport('Tranactions');
      $fileName = "Tranactions.xlsx";
      $filePath = "{$tempDir}/{$fileName}";

      Excel::store($export, $fileName, 'temp_export');

      $excelFiles[] = $filePath;

      $export = new YourExcelTranactionsExport('Users');
      $fileName = "Users.xlsx";
      $filePath = "{$tempDir}/{$fileName}";

      Excel::store($export, $fileName, 'temp_export');

      $excelFiles[] = $filePath;

      // Create a zip file containing all Excel files
      $zipFileName = 'MapIner.zip';
      $zipFilePath = "{$tempDir}/{$zipFileName}";

      $zip = new ZipArchive;
      $zip->open($zipFilePath, ZipArchive::CREATE);

      foreach ($excelFiles as $excelFile) {
          $zip->addFile($excelFile, basename($excelFile));
      }

      $zip->close();
      File::cleanDirectory(public_path('files'));
      $publicZipFilePath = public_path('files/MapIner.zip');
      File::move($zipFilePath, $publicZipFilePath);

      File::cleanDirectory(storage_path('app/temp_export'));

      return response()->json(['url' => asset('files/MapIner.zip')]);


    } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'error' => $e->getMessage(),
        ], 401);
    }


    // try {
    //   $filename = 'Tranactions.xlsx';
    //   return Excel::download(new YourExcelTranactionsExport(), $filename);


    // } catch (\Exception $e) {
    //   return response()->json([
    //     'status' => 0,
    //     'error' => $e->getMessage(),
    //   ],401);
    // }
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
