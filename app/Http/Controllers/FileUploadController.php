<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = pathinfo($image, PATHINFO_FILENAME) . '.webp';
            $imagePath = "uploads/$imageName";

            $webpImage = Image::make($image)->encode('webp', 90); // 90 valor de la calidad
            Storage::disk('public')->put($imagePath, $webpImage);

            return response()->json([
                'success' => true,
                'path' => $imagePath,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
        ]);
    }

    public function getFile($filename)
    {
        if (Storage::disk('public')->exists("uploads/$filename")) {
            $file = Storage::disk('public')->get("uploads/$filename");
            $mimeType = Storage::disk('public')->mimeType("uploads/$filename");

            return response($file, 200)->header('Content-Type', $mimeType);
        }

        return response()->json([
            'success' => false,
            'message' => 'File not found',
        ], 404);
    }
    public function listUploads()
    {
        $files = Storage::disk('public')->files('uploads');
        $fileNames = array_map(function ($file) {
            return basename($file);
        }, $files);

        return response()->json($fileNames);
    }

    public function deleteFile($filename)
    {
        $path = "uploads/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }
}
