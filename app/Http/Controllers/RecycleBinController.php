<?php

namespace App\Http\Controllers;

use App\Models\CloudFile;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecycleBinController extends Controller
{
    public function recycleBin()
    {
        $folders = Folder::onlyTrashed()->where('user_id', Auth::id())->get();
        $files   = CloudFile::onlyTrashed()->where('user_id', Auth::id())->get();

        return view('recycle-bin', compact('folders', 'files'));
    }

    public function restore($type, $id)
    {
        if ($type === 'file') {
            $file = CloudFile::onlyTrashed()->findOrFail($id);
            $file->restore();
        } elseif ($type === 'folder') {
            $folder = Folder::onlyTrashed()->findOrFail($id);
            $folder->restore();
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($type, $id)
    {
        if ($type === 'file') {
            $file = CloudFile::onlyTrashed()->findOrFail($id);
            $file->forceDelete();
        } elseif ($type === 'folder') {
            $folder = Folder::onlyTrashed()->findOrFail($id);
            $folder->forceDelete();
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        return response()->json(['success' => true]);
    }
}
