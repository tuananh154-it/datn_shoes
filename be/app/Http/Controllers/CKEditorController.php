<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads', $filename);
            $url = Storage::url('uploads/' . $filename);

            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }
        return response()->json(['uploaded' => false]);
    }
    
}
