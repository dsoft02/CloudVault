<?php
namespace App\Http\Controllers;

use App\Models\CloudFile;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CloudFileController extends Controller
{
    public function moveToRecycleBin($id)
    {
        $file = CloudFile::where('user_id', Auth::id())->findOrFail($id);
        $file->delete();

        return response()->json(['message' => 'File moved to Recycle Bin successfully.']);
    }

    private function resolveFolderPath($path)
    {
        if (empty(trim($path))) {
            return null; // Root directory
        }

        $segments = explode('/', trim($path, '/'));
        $parentId = null;

        foreach ($segments as $name) {
            $folder = Folder::whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->where('user_id', Auth::id())
                ->where('parent_id', $parentId)
                ->first();

            if (! $folder) {
                return null; // Invalid path
            }

            $parentId = $folder->id;
        }

        return $parentId;
    }

    public function move(Request $request, $id)
    {
        $file        = CloudFile::where('user_id', Auth::id())->findOrFail($id);
        $newFolderId = $this->resolveFolderPath($request->new_path);

        if ($newFolderId === null && trim($request->new_path) !== "") {
            return response()->json(['message' => 'Invalid folder path'], 400);
        }

        $existingFile = CloudFile::where('folder_id', $newFolderId)
            ->whereRaw('LOWER(file_name) = ?', [strtolower($file->file_name)])
            ->where('user_id', Auth::id())
            ->first();

        if ($existingFile) {
            if ($request->override_existing) {
                Storage::disk('local')->delete($existingFile->file_path);
                $existingFile->delete();
            } else {
                return response()->json(['message' => 'A file with this name already exists in the destination folder.'], 400);
            }
        }

        $oldPath       = $file->file_path;
        $folder        = Folder::find($newFolderId);
        $newFolderPath = $this->getFolderPathFromBreadcrumb($folder);
        $newPath       = "uploads/" . Auth::id() . "/{$newFolderPath}/{$file->file_name}.enc";

        Storage::disk('local')->copy($oldPath, $newPath);
        Storage::disk('local')->delete($oldPath);

        $file->update([
            'folder_id' => $newFolderId,
            'file_path' => $newPath,
        ]);

        return response()->json(['message' => 'File moved successfully!']);
    }

    public function copy(Request $request, $id)
    {
        $file        = CloudFile::where('user_id', Auth::id())->findOrFail($id);
        $newFolderId = $this->resolveFolderPath($request->new_path);

        if ($newFolderId === null) {
            return response()->json(['message' => 'Invalid folder path'], 400);
        }

        $folder        = Folder::find($newFolderId);
        $newFolderPath = $this->getFolderPathFromBreadcrumb($folder);

        $newFileName = $this->generateUniqueFileName($file->file_name, $newFolderId);

        $newFilePath = "uploads/" . Auth::id() . "/{$newFolderPath}/{$newFileName}.enc";

        Storage::disk('local')->copy($file->file_path, $newFilePath);

        $newFile            = $file->replicate();
        $newFile->folder_id = $newFolderId;
        $newFile->file_name = $newFileName;
        $newFile->file_path = $newFilePath;
        $newFile->save();

        return response()->json(['message' => 'File copied successfully!']);
    }

    private function generateUniqueFileName($fileName, $folderId)
    {
        $originalName = pathinfo($fileName, PATHINFO_FILENAME);
        $extension    = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName  = $fileName;
        $counter      = 1;

        while (CloudFile::where('folder_id', $folderId)
            ->whereRaw('LOWER(file_name) = ?', [strtolower($newFileName)])
            ->exists()) {
            $newFileName = "{$originalName} ({$counter}).{$extension}";
            $counter++;
        }

        return $newFileName;
    }

    public function info($id)
    {
        $file = CloudFile::withTrashed()->where('user_id', Auth::id())->findOrFail($id);

        return response()->json([
            'name'       => $file->file_name,
            'size'       => formatSize($file->file_size),
            'created_at' => $file->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $file->updated_at->format('Y-m-d H:i:s'),
            'deleted_at' => $file->deleted_at ? $file->deleted_at->format('Y-m-d H:i:s') : null,
            'path'       => $this->getFilePath($file),
            'file_icon'  => getFileIcon($file->file_name, false),
        ]);
    }

    private function getFilePath($file)
    {
        if (! $file->folder) {
            return "Cloud Drive";
        }
        return $this->getFolderPath($file->folder);
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

    public function showUploadForm(?Folder $folder = null)
    {
        return view('folders.upload', compact('folder'));
    }

    public function uploadFile(Request $request, ?Folder $folder = null)
    {
        $request->validate([
            'files'             => 'required|array',
            'files.*'           => 'file|max:51200',
            'encryption_type'   => 'required|in:single,multiple',
            'encryption_key'    => 'nullable|string|min:8|required_if:encryption_type,single',
            'encryption_keys'   => 'nullable|array|required_if:encryption_type,multiple',
            'encryption_keys.*' => 'nullable|string|min:8|required_if:encryption_type,multiple',
        ]);

        $user           = Auth::user();
        $files          = $request->file('files');
        $encryptionType = $request->encryption_type;
        $singleKey      = $encryptionType === 'single' ? $request->encryption_key : null;
        $encryptionKeys = $encryptionType === 'multiple' ? $request->encryption_keys : [];

        $uploadedFiles = [];

        foreach ($files as $index => $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileSize = $file->getSize();

            $encryptionKey = $encryptionType === 'single' ? $singleKey : $encryptionKeys[$index];
            $hashedKey     = hash('sha256', $encryptionKey);
            $iv            = substr($hashedKey, 0, 16);

            $fileContent      = file_get_contents($file->getRealPath());
            $encryptedContent = openssl_encrypt($fileContent, 'AES-256-CBC', $hashedKey, 0, $iv);

            $folderPath = $this->getFolderPathFromBreadcrumb($folder);
            $cloudPath  = "uploads/{$user->id}/{$folderPath}";

            Storage::disk('local')->makeDirectory($cloudPath);

            $encryptedFilePath = "{$cloudPath}/{$fileName}.enc";

            Storage::disk('local')->put($encryptedFilePath, $encryptedContent);

            CloudFile::create([
                'user_id'       => $user->id,
                'folder_id'     => $folder ? $folder->id : null,
                'file_name'     => $fileName,
                'file_path'     => $encryptedFilePath,
                'file_size'     => $fileSize,
                'encrypted_key' => base64_encode($hashedKey),
            ]);

            $uploadedFiles[] = [
                'filename'       => $fileName,
                'encryption_key' => $encryptionKey,
            ];
        }

        $txtContent = "Uploaded Files & Encryption Keys:\n";
        foreach ($uploadedFiles as $file) {
            $txtContent .= "File: {$file['filename']} | Key: {$file['encryption_key']}\n";
        }

        $txtFileName  = 'encryption_keys_' . time() . '.txt';
        $txtDirectory = storage_path('app/temp');
        if (! file_exists($txtDirectory)) {
            mkdir($txtDirectory, 0777, true);
        }

        $txtFilePath = "{$txtDirectory}/{$txtFileName}";
        file_put_contents($txtFilePath, $txtContent);

        $downloadUrl = route('temp-files.download', ['file' => $txtFileName]);

        return $folder
        ? redirect()->route('folders.open', $folder->id)->with([
            'success'       => 'Files uploaded and encrypted successfully!',
            'download_link' => $downloadUrl,
        ])
        : redirect()->route('dashboard')->with([
            'success'       => 'Files uploaded and encrypted successfully!',
            'download_link' => $downloadUrl,
        ]);
    }

    public function downloadFile(Request $request, CloudFile $file)
    {
        $decryptionKey = $request->query('key');

        if (! $decryptionKey) {
            return back()->with('error', 'Decryption key is required.');
        }

        $storedKey   = base64_decode($file->encrypted_key);
        $providedKey = hash('sha256', $decryptionKey);

        if ($storedKey !== $providedKey) {
            return back()->with('error', 'Invalid decryption key.');
        }

        $encryptedFilePath = $file->file_path;
        $encryptedContent  = Storage::disk('local')->get($encryptedFilePath);

        $iv               = substr($providedKey, 0, 16);
        $decryptedContent = openssl_decrypt($encryptedContent, 'AES-256-CBC', $providedKey, 0, $iv);

        if (! $decryptedContent) {
            return back()->with('error', 'File decryption failed.');
        }

        return new StreamedResponse(function () use ($decryptedContent) {
            echo $decryptedContent;
        }, 200, [
            'Content-Type'        => $file->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $file->file_name . '"',
        ]);
    }

    public function downloadRawFile(CloudFile $file)
    {
        $filePath = $file->file_path;

        if (! Storage::disk('local')->exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('local')->download($filePath, $file->file_name . '.enc');
    }

    public function showSharedFile($token)
    {
        $file = CloudFile::where('share_token', $token)->first();

        if (! $file) {
            abort(404);
        }

        return view('files.shared_download', compact('file'));
    }

    public function downloadSharedFile(Request $request)
    {
        $file = CloudFile::where('share_token', $request->input('token'))->first();

        if (! $file) {
            return back()->with('error', 'Invalid or expired link.')
                ->with('errorMessage', 'Invalid or expired link.');
        }

        $decryptionKey = $request->input('decryption_key');
        if (! $decryptionKey) {
            return back()->with('error', 'Decryption key is required.')
                ->with('errorMessage', 'Decryption key is required.');
        }

        $storedKey   = base64_decode($file->encrypted_key);
        $providedKey = hash('sha256', $decryptionKey);

        if ($storedKey !== $providedKey) {
            return back()->with('error', 'Invalid decryption key. Please try again.')
                ->with('errorMessage', 'Invalid decryption key. Please try again.');
        }

        $encryptedFilePath = $file->file_path;
        $encryptedContent  = Storage::disk('local')->get($encryptedFilePath);

        $iv               = substr($providedKey, 0, 16);
        $decryptedContent = openssl_decrypt($encryptedContent, 'AES-256-CBC', $providedKey, 0, $iv);

        if (! $decryptedContent) {
            return back()->with('error', 'Decryption failed. Please check your key and try again.')
                ->with('errorMessage', 'Decryption failed. Please check your key and try again.');
        }

        return Response::streamDownload(function () use ($decryptedContent) {
            echo $decryptedContent;
        }, $file->file_name, [
            'Content-Type'        => $file->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $file->file_name . '"',
        ]);
    }

    public function generateShareLink(CloudFile $file)
    {
        if (! $file->share_token) {
            $file->share_token = Str::uuid();
            $file->save();
        }

        return response()->json([
            'success'   => true,
            'token'     => $file->share_token,
            'share_url' => url("/files/share/{$file->share_token}"),
        ]);

    }

    public function downloadSharedRawFile(Request $request)
    {
        $file = CloudFile::where('share_token', $request->input('token'))->first();

        if (! $file) {
            return back()->with('error', 'Invalid or expired link.');
        }

        $filePath = $file->file_path;

        if (! Storage::disk('local')->exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('local')->download($filePath, $file->file_name);
    }

    private function getFolderPathFromBreadcrumb($folder): string
    {
        $breadcrumb  = $folder ? $folder->getBreadcrumb() : [];
        $folderNames = collect($breadcrumb)
            ->pluck('name')
            ->map(fn($name) => Str::slug($name))
            ->implode('/');

        return $folderNames ? Str::slug('Cloud Drive') . "/{$folderNames}" : Str::slug('Cloud Drive');
    }

}
