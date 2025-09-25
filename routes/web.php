<?php

use App\Http\Controllers\FormulirPublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/formulir', [FormulirPublicController::class, 'create'])->name('formulir.create');
Route::post('/formulir', [FormulirPublicController::class, 'store'])->name('formulir.store');
