<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FileController;



Route::get('/uploads', [FileController::class, 'index']);
Route::post('/uploads', [FileController::class, 'upload_bulk']);
Route::get('/download/{fileName}', [FileController::class, 'download']);