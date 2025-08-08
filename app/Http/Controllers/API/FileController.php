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

    public function download($fileName)
    {
        // $file = File::find($id);
        // dd($fileName);
        $file = File::where('fileName',$fileName)->orderBy('id', 'desc')->first();
        // $flights = Flight::where('destination', 'Paris')->get();
        // dd($$fileName.'txt');

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }
        $path = storage_path('app/public/' . $file->file);
        // $path = storage_path('app/public/uploads/uvmvrnnvXipGJeLPsohw0wEYJD1z8CDk1F4Fb7QH.txt');

        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->download($path, $file->name);
    }
}
