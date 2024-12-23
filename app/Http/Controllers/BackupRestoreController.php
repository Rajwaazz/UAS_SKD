<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class BackupRestoreController extends Controller
{
    public function listBackups()
    {
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $backupFiles = array_filter(
            glob($backupDir . '/*.sql'),
            'is_file'
        );
        // Transform file paths to include full information
        $backupsWithInfo = array_map(function ($file) {
            return [
                'path' => $file,
                'filename' => basename($file),
                'size' => file_exists($file) ? round(filesize($file) / 1024 / 1024, 2) : 0,
                'created_at' => file_exists($file) ? date('Y-m-d H:i:s', filemtime($file)) : null
            ];
        }, $backupFiles);
        return view('admin.backup-list', [
            'backups' => $backupsWithInfo
        ]);
    }
    public function backup()
    {
        try {
            // Use Laravel's database dumping method
            $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
            $path = storage_path("app/backups/{$filename}");
            // Ensure backup directory exists
            if (!is_dir(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            $command = sprintf(
                'mysqldump -u %s %s > "%s"',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.database'),
                $path
            );
            exec($command, $output, $returnVar);
            if ($returnVar !== 0) {
                throw new \Exception('mysqldump command failed.');
            }
            exec($command);
            return back()->with('success', "Database backed up successfully: {$filename}");
        } catch (\Exception $e) {
            return back()->with('error', 'Database backup failed: ' . $e->getMessage());
        }
    }
    public function downloadBackup($filename)
    {
        // Gunakan path lengkap
        $fullPath = storage_path("app/backups/{$filename}");
        // Validasi file backup
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File backup tidak ditemukan');
        }
        // Pastikan file adalah file SQL
        if (!str_ends_with($filename, '.sql')) {
            return back()->with('error', 'File backup tidak valid');
        }
        // Kembalikan file untuk diunduh
        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }
    public function deleteBackup($filename)
    {
        try {
            $fullPath = storage_path("app/backups/{$filename}");
            // Validasi file sebelum dihapus
            if (file_exists($fullPath)) {
                // Tutup file handler jika ada proses yang membukanya
                clearstatcache(true, $fullPath);
                // Cek apakah file dapat dihapus
                if (!is_writable($fullPath)) {
                    return back()->with('error', "File {$filename} sedang digunakan atau tidak dapat diakses.");
                }
                unlink($fullPath);
                return back()->with('success', "Backup {$filename} berhasil dihapus");
            }
            return back()->with('error', 'File backup tidak ditemukan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus file backup: ' . $e->getMessage());
        }
    }
    public function restore(Request $request)
{
    try {
        $file = $request->file('backup_file');
        $path = $file->storeAs('backups', $file->getClientOriginalName());
        $fullPath = storage_path("app/{$path}");
        $command = sprintf(
            'mysql -u %s %s < "%s"',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.database'),
            $fullPath
        );
        
        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            throw new \Exception('mysql command failed.');
        }
        return back()->with('success', 'Database restored successfully');
    } catch (\Exception $e) {
        Log::error("Restore Error: " . $e->getMessage());
        return back()->with('error', 'Database restore failed: ' . $e->getMessage());
    }
}
}