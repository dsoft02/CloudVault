<?php
namespace App\Http\Controllers;

use App\Models\CloudFile;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $parentId = $request->parent_id ?? null;

        $existingFolder = Folder::where('user_id', Auth::id())
            ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->where('parent_id', $parentId)
            ->exists();

        if ($existingFolder) {
            return redirect()->back()->with('error', 'A folder with this name already exists in the same directory.');
        }

        Folder::create([
            'user_id'   => Auth::id(),
            'name'      => $request->name,
            'parent_id' => $parentId,
        ]);

        return redirect()->back()->with('success', 'Folder created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = Folder::where('user_id', Auth::id())->findOrFail($id);

        $existingFolder = Folder::where('user_id', Auth::id())
            ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->where('parent_id', $folder->parent_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($existingFolder) {
            return redirect()->back()->with('error', 'A folder with this name already exists in the same directory.');
        }

        $folder->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'Folder updated successfully!');
    }

    public function moveToRecycleBin($id)
    {
        $folder = Folder::where('user_id', Auth::id())->findOrFail($id);

        $this->deleteSubfolders($folder);

        CloudFile::where('folder_id', $folder->id)->update(['deleted_at' => now()]);

        $folder->delete();

        return response()->json(['message' => 'Folder and its contents moved to Recycle Bin successfully.']);
    }

    private function deleteSubfolders($folder)
    {
        foreach ($folder->subfolders as $subfolder) {
            $this->deleteSubfolders($subfolder);
            CloudFile::where('folder_id', $subfolder->id)->update(['deleted_at' => now()]);
            $subfolder->delete();
        }
    }

    private function resolveFolderPath($path)
    {
        if (empty(trim($path))) {
            return null;
        }

        $segments = explode('/', trim($path, '/'));
        $parentId = null;

        foreach ($segments as $name) {
            $folder = Folder::whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->where('user_id', Auth::id())
                ->where('parent_id', $parentId)
                ->first();

            if (! $folder) {
                return null;
            }

            $parentId = $folder->id;
        }

        return $parentId;
    }

    public function move(Request $request, $id)
    {
        $folder      = Folder::where('user_id', Auth::id())->findOrFail($id);
        $newParentId = $this->resolveFolderPath($request->new_path);

        if ($newParentId === null && trim($request->new_path) !== "") {
            return response()->json(['message' => 'Invalid folder path'], 400);
        }

        if ($newParentId === $folder->id || $this->isSubfolder($folder->id, $newParentId)) {
            return response()->json(['message' => 'Cannot move a folder inside itself or its subfolder.'], 400);
        }

        $existingFolder = Folder::where('parent_id', $newParentId)
            ->whereRaw('LOWER(name) = ?', [strtolower($folder->name)])
            ->where('user_id', Auth::id())
            ->first();

        if ($existingFolder) {
            if ($request->override_existing) {
                $existingFolder->delete();
            } else {
                $folder->name = $folder->name . ' (copy)';
            }
        }

        $folder->update(['parent_id' => $newParentId]);

        return response()->json(['message' => 'Folder moved successfully!']);
    }

    public function copy(Request $request, $id)
    {
        $folder      = Folder::where('user_id', Auth::id())->findOrFail($id);
        $newParentId = $this->resolveFolderPath($request->new_path);

        if ($newParentId === null && trim($request->new_path) !== "") {
            return response()->json(['message' => 'Invalid folder path'], 400);
        }

        $existingFolder = Folder::where('parent_id', $newParentId)
            ->whereRaw('LOWER(name) = ?', [strtolower($folder->name)])
            ->where('user_id', Auth::id())
            ->first();

        if ($existingFolder) {
            if ($request->override_existing) {
                $existingFolder->delete();
            } else {
                $folder->name = $folder->name . ' (copy)';
            }
        }

        $newFolder            = $folder->replicate();
        $newFolder->parent_id = $newParentId;
        $newFolder->save();

        $this->copySubfoldersAndFiles($folder, $newFolder);

        return response()->json(['message' => 'Folder copied successfully!']);
    }

    private function copySubfoldersAndFiles($oldFolder, $newFolder)
    {
        foreach ($oldFolder->subfolders as $subfolder) {
            $newSubfolder            = $subfolder->replicate();
            $newSubfolder->parent_id = $newFolder->id;
            $newSubfolder->save();

            $this->copySubfoldersAndFiles($subfolder, $newSubfolder);
        }

        foreach ($oldFolder->files as $file) {
            $newFile            = $file->replicate();
            $newFile->folder_id = $newFolder->id;
            $newFile->save();
        }
    }
    private function isSubfolder($folderId, $newParentId)
    {
        while ($newParentId) {
            if ($newParentId === $folderId) {
                return true;
            }

            $parentFolder = Folder::find($newParentId);
            if (! $parentFolder) {
                break;
            }

            $newParentId = $parentFolder->parent_id;
        }

        return false;
    }

    public function info($id)
    {
        $folder = Folder::withTrashed()->where('user_id', Auth::id())->findOrFail($id);

        return response()->json([
            'name'          => $folder->name,
            'size'          => formatSize($folder->getTotalSize()),
            'created_at'    => $folder->created_at->format('Y-m-d H:i:s'),
            'updated_at'    => $folder->updated_at->format('Y-m-d H:i:s'),
            'deleted_at'    => $folder->deleted_at ? $folder->deleted_at->format('Y-m-d H:i:s') : null,
            'path'          => $this->getFolderPath($folder),
            'file_icon'     => getFileIcon($folder->name, true),
            'total_files'   => $this->countAllFiles($folder),
            'total_folders' => $this->countAllSubfolders($folder),
        ]);
    }

    private function countAllSubfolders($folder)
    {
        $count = $folder->subfolders()->count();

        foreach ($folder->subfolders as $subfolder) {
            $count += $this->countAllSubfolders($subfolder);
        }

        return $count;
    }

    private function countAllFiles($folder)
    {
        $count = $folder->files()->count();

        foreach ($folder->subfolders as $subfolder) {
            $count += $this->countAllFiles($subfolder);
        }

        return $count;
    }

    private function getFolderPath($folder)
    {
        $path = [];

        while ($folder && $folder->parent_id) {
            $path[] = $folder->name;
            $folder = Folder::find($folder->parent_id);
        }

        $path[] = $folder->name;

        return 'Cloud Drive > ' . implode(' > ', array_reverse($path));
    }

    public function open($id)
    {
        $folder       = Folder::where('user_id', Auth::id())->findOrFail($id);
        $folder->path = $this->getFolderPath($folder);

        $folders = Folder::where('user_id', Auth::id())
            ->where('parent_id', $id)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($folder) {
                $folder->total_size = $folder->getTotalSize();
                $folder->path       = $this->getFolderPath($folder);
                return $folder;
            });

        $files = CloudFile::where('user_id', Auth::id())
            ->where('folder_id', $id)
            ->orderBy('file_name', 'asc')
            ->get();

        return view('folders.view', compact('folder', 'folders', 'files'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (! $query) {
            return redirect()->route('dashboard');
        }

        $folders = Folder::where('user_id', Auth::id())
            ->where('name', 'LIKE', "%{$query}%")
            ->get();

        $files = CloudFile::where('user_id', Auth::id())
            ->where('file_name', 'LIKE', "%{$query}%")
            ->get();

        return view('folders.search_results', compact('folders', 'files', 'query'));
    }

}
