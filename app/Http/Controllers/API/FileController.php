<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    public function index(): JsonResponse
    {
        $files = File::all();
        return response()->json($files);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'fileName' => 'required|max:64',
            'file' => 'required|file|mimes:txt|max:1024'
        ]);

        if ($request->hasFile('file')) {
            $fileName = $request->file('file');
            $file = $fileName->store('uploads', 'public');

            $fileModel = new File();
            $fileModel->fileName = $fileName->getClientOriginalName();
            $fileModel->file = $file;
            $fileModel->save();

            return response()->json(['message' => 'File uploaded successfully', 'file' => $fileModel], 200);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}
