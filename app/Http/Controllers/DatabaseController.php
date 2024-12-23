<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class DatabaseController extends Controller
{
    // Method untuk menampilkan halaman backup dan restore
    public function showBackupList()
    {
        $backupFolder = storage_path('app/backups');
        $backups = File::files($backupFolder);
        
        return view('admin.backup-list', compact('backups'));
    }
    // Method untuk backup database
    public function backupDatabase()
    {
        try {
            $now = Carbon::now();
            $date = $now->toDateString(); // YYYY-MM-DD
            $time = $now->toTimeString(); // HH:MM:SS
            $time = str_replace(':', '-', $time); // Replace colon with dash
            $backupFileName = "backup_{$date}_{$time}.sql";
            $backupFolder = storage_path('app/backups'); // Folder for backups
            $backupFilePath = $backupFolder . '/' . $backupFileName;
            
            // Ensure 'backups' folder exists
            if (!File::exists($backupFolder)) {
                File::makeDirectory($backupFolder, 0755, true);
            }
            $mysqlCommand = "mysqldump -u root movieapps | gzip > {$backupFilePath}.gz";
            // Execute the backup command
            exec($mysqlCommand);
            // Redirect back with success message
            return redirect()->route('admin.backup-list')->with('success', 'Backup database berhasil disimpan!');
        } catch (\Exception $error) {
            return redirect()->route('admin.backup-list')->with('error', 'Error while backing up: ' . $error->getMessage());
        }
    }
    // Method untuk restore database
    public function restoreDatabase(Request $request)
    {
        $backupFileName = $request->input('backup_file');
        try {
            $backupFolder = storage_path('app/backups');
            $backupFilePath = $backupFolder . '/' . $backupFileName;
            // Check if backup file exists
            if (!File::exists($backupFilePath)) {
                return redirect()->route('admin.backup-list')->with('error', "Backup file not found: {$backupFilePath}");
            }
            // Decompress if the file is gzipped
            $decompressedFilePath = preg_replace('/\.gz$/', '', $backupFilePath);
            if (Str::endsWith($backupFileName, '.gz')) {
                $this->decompressGzipFile($backupFilePath, $decompressedFilePath);
            }
            
            $dbUser = env('DB_USERNAME', 'root');
            $dbPassword = env('DB_PASSWORD');
            $dbName = env('DB_DATABASE', 'movieapps');
            $mysqlCommand = "mysql -u {$dbUser} " . ($dbPassword ? "-p{$dbPassword} " : "") . "{$dbName} < {$decompressedFilePath}";
            // Execute restore command
            exec($mysqlCommand);
            return redirect()->route('admin.backup-list')->with('success', 'Database berhasil direstore!');
        } catch (\Exception $error) {
            return redirect()->route('admin.backup-list')->with('error', 'Error while restoring database: ' . $error->getMessage());
        }
    }
    // Helper function to decompress .gz files
    private function decompressGzipFile($source, $destination)
    {
        $readStream = fopen($source, 'rb');
        $writeStream = fopen($destination, 'wb');
        $gz = gzopen($source, 'rb');
        while ($string = gzread($gz, 4096)) {
            fwrite($writeStream, $string);
        }
        gzclose($gz);
        fclose($readStream);
        fclose($writeStream);
    }
}
