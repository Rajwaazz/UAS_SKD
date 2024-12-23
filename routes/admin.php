<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\BackupRestoreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SchedulesController;

Route::prefix('admin')->middleware(['web'])->group(function () {  // Tambahkan middleware web
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('register', [RegisterController::class, 'create'])->name('admin.register');
        Route::post('register', [RegisterController::class, 'store']);

        Route::get('login', [LoginController::class, 'create'])->name('admin.login');
        Route::post('login', [LoginController::class, 'store']);
        
        Route::get('/verify-otp', [RegisterController::class, 'showOTP'])->name('admin.showotp');
        Route::post('/verify-otp', [RegisterController::class, 'verifyOTP']);
    });

    Route::middleware(['auth:admin', 'role:admin'])->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::get('movies', [MovieController::class, 'index'])->name('admin.dashboard.film');
        Route::get('movies/create', [MovieController::class, 'create'])->name('admin.dashboard.createfilm');
        Route::post('movies/store', [MovieController::class, 'store'])->name('admin.movies.store');
        Route::get('movies/{film}/edit', [MovieController::class, 'edit'])->name('admin.dashboard.editfilm');
        Route::put('movies/edit/{film}', [MovieController::class, 'update'])->name('admin.dashboard.film.update');
        Route::delete('movies/{film}', [MovieController::class, 'destroy'])->name('admin.dashboard.film.destroy');

        Route::get('schedules', [SchedulesController::class, 'index'])->name('admin.dashboard.schedules');
        Route::get('schedules/create', [SchedulesController::class, 'create'])->name('admin.dashboard.createschedules');
        Route::post('schedules/store', [SchedulesController::class, 'store']);
        Route::delete('schedules/{id}', [SchedulesController::class, 'destroy']);
        Route::post('logout', [LoginController::class, 'destroy'])->name('admin.logout');

            Route::get('/backups', [BackupRestoreController::class, 'listBackups'])->name('admin.backup-list');
    Route::get('/backup/download/{filename}', [BackupRestoreController::class, 'downloadBackup'])
        ->name('backup.download');
    Route::delete('/backup/delete/{filename}', [BackupRestoreController::class, 'deleteBackup'])
        ->name('backup.delete');
    Route::get('/backup/create', [BackupRestoreController::class, 'backup'])->name('backup');
    Route::post('/restore', [BackupRestoreController::class, 'restore'])->name('restore');

    });
});

Route::middleware(['auth:admin', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});
