<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\UploadArsipPeg;
use App\Http\Controllers\FormulirPublicController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/formulir', [FormulirPublicController::class, 'create'])->name('formulir.create');
Route::post('/formulir', [FormulirPublicController::class, 'store'])->name('formulir.store');

Route::get('/upload-arsip', [UploadController::class, 'showForm'])->name('upload.form');
Route::post('/upload-arsip', [UploadController::class, 'upload'])->name('upload.submit');

// Redirect root to upload form
Route::get('/', function () {
    return redirect('/upload-arsip');
});
