<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FileController;



Route::get('/uploads', [FileController::class, 'index']);