<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        // List files from S3 under the 'uploads/' prefix
        $files = Storage::files('uploads');

        return view('files.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $path = Storage::disk('s3')->put('uploads', $file);

        return redirect('/')->with('success', 'File uploaded to S3 successfully! path:'.$path);
    }
}
