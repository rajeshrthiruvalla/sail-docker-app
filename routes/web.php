<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FileController::class, 'index']);
Route::post('/upload', [FileController::class, 'upload'])->name('file.upload');