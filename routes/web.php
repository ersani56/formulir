<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FormulirPublicController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/formulir', [FormulirPublicController::class, 'create'])->name('formulir.create');
Route::post('/formulir', [FormulirPublicController::class, 'store'])->name('formulir.store');

Route::get('/upload-arsip', [UploadController::class, 'showForm'])->name('upload.form');
Route::post('/upload-arsip', [UploadController::class, 'upload'])->name('upload.submit');
Route::get('/check-files/{nip}', [UploadController::class, 'checkFiles'])->name('check.files');
Route::get('/download-report', [ReportController::class, 'downloadReport'])->name('download.report');

Route::get('/report', [ReportController::class, 'showReportPage'])->name('report.page');
Route::post('/generate-report', [ReportController::class, 'generateReport'])->name('generate.report');
Route::get('/api/statistics', [ReportController::class, 'getStatistics'])->name('api.statistics');

// Test email route
Route::get('/test-email', function () {
    try {
        Mail::raw('Test email dari BKPSDM System', function ($message) {
            $message->to('test@example.com')
                    ->subject('Test Email dari Mailtrap');
        });

        return 'Email berhasil dikirim! Cek di Mailtrap Inbox.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
