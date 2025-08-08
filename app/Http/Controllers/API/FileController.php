<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\File;


class FileController extends Controller
{
    public function index(): JsonResponse
    {
        $files = File::all();
        return response()->json($files);
    }
}
