<?php
namespace App\Http\Controllers;

use App\Models\CloudFile;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $folders = Folder::where('user_id', $userId)
            ->whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($folder) {
                $folder->total_size = $folder->getTotalSize();
                return $folder;
            });

        $files = CloudFile::where('user_id', $userId)
            ->whereNull('folder_id')
            ->orderBy('file_name', 'asc')
            ->get();

        return view('dashboard', compact('folders', 'files'));
    }
}
