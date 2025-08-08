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
            'file' => 'required|file|mimes:txt|max:100'
        ]);

        if ($request->hasFile('file')) {
            $fileName = $request->fileName;
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');

            $fileModel = new File();
            $fileModel->fileName = $fileName;
            $fileModel->file = $path;
            $fileModel->save();

            return response()->json(['message' => 'File uploaded successfully', 'file' => $fileModel], 201);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function upload_bulk(Request $request): JsonResponse
    {   
        $request->validate([
            'file.*' => 'required|file|mimes:txt|max:100'
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $fileName = $file->getClientOriginalName();
                $path =  $file->store('uploads', 'public');
                $uploadedFile = File::create([
                    'fileName' => $fileName,
                    'file' => $path,
                ]);
                $uploadedFiles[] = $uploadedFile;
            }
        }
        return response()->json(['message' => 'Files uploaded successfully', 'files' => $uploadedFiles]);
    }


    public function download($fileName)
    {
        $file = File::where('fileName',$fileName)->orderBy('id', 'desc')->first();
        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }
        $path = storage_path('app/public/' . $file->file);
        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return response()->download($path, $file->name);
    }
}
