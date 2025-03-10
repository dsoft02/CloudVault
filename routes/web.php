<?php

use App\Http\Controllers\CloudFileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecycleBinController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    //Folder Routes
    Route::prefix('folders')->name('folders.')->group(function () {
        Route::get('/{id}', [FolderController::class, 'open'])->name('open');
        Route::get('/{id}/share', [FolderController::class, 'share'])->name('share');
        Route::get('/{id}/info', [FolderController::class, 'info'])->name('info');
        Route::get('/{id}/rename', [FolderController::class, 'rename'])->name('rename');
        Route::post('/{id}/move', [FolderController::class, 'move'])->name('move');
        Route::post('/{id}/copy', [FolderController::class, 'copy'])->name('copy');
        Route::post('/store', [FolderController::class, 'store'])->name('store');
        Route::post('/update/{id}', [FolderController::class, 'update'])->name('update');
        Route::post('/{id}/trash', [FolderController::class, 'moveToRecycleBin'])->name('trash');
    });

    Route::get('/search', [FolderController::class, 'search'])->name('search');

    //File Routes
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/{id}/share', [CloudFileController::class, 'share'])->name('share');
        Route::get('/{id}/info', [CloudFileController::class, 'info'])->name('info');
        Route::get('/{id}/rename', [CloudFileController::class, 'rename'])->name('rename');
        Route::post('/{id}/move', [CloudFileController::class, 'move'])->name('move');
        Route::post('/{id}/copy', [CloudFileController::class, 'copy'])->name('copy');
        Route::post('/{id}/trash', [CloudFileController::class, 'moveToRecycleBin'])->name('trash');
        Route::post('/{file}/generate-share-link', [CloudFileController::class, 'generateShareLink'])->name('generate.share');

        // Download Routes
        Route::get('/{file}/download', [CloudFileController::class, 'downloadFile'])->name('download');
        Route::get('/{file}/download/raw', [CloudFileController::class, 'downloadRawFile'])->name('download.raw');
    });

    // Recycle Bin
    Route::prefix('recycle-bin')->name('recycle.')->group(function () {
        Route::get('/', [RecycleBinController::class, 'recycleBin'])->name('bin');
        Route::post('/restore/{type}/{id}', [RecycleBinController::class, 'restore'])->name('restore');
        Route::delete('/delete/{type}/{id}', [RecycleBinController::class, 'destroy'])->name('delete');
    });

    // Upload Routes
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::get('/', [CloudFileController::class, 'showUploadForm'])->name('root.form');
        Route::post('/', [CloudFileController::class, 'uploadFile'])->name('root');
        Route::get('/folders/{folder}', [CloudFileController::class, 'showUploadForm'])->name('folders.form');
        Route::post('/folders/{folder}', [CloudFileController::class, 'uploadFile'])->name('folders');
    });


   // Temporary File Routes
    Route::prefix('temp-files')->name('temp-files.')->group(function () {
        Route::get('/download/{file}', function ($file) {
            $filePath = storage_path("app/temp/{$file}");
            abort_unless(file_exists($filePath), 404, 'File not found.');
            return response()->download($filePath)->deleteFileAfterSend(true);
        })->name('download');

        Route::delete('/delete/{file}', function ($file) {
            $filePath = storage_path("app/temp/{$file}");
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return response()->json(['message' => 'File deleted']);
        })->name('delete');
    });

});

// Public Shared File Routes
Route::prefix('files/share')->name('files.shared.')->group(function () {
    Route::get('/{token}', [CloudFileController::class, 'showSharedFile'])->name('show');
    Route::post('/download', [CloudFileController::class, 'downloadSharedFile'])->name('download');
    Route::get('/download/raw', [CloudFileController::class, 'downloadSharedRawFile'])->name('download.raw');
});

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect()->route('index');
});


require __DIR__ . '/auth.php';
