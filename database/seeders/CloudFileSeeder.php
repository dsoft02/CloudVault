<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CloudFile;
use App\Models\User;
use App\Models\Folder;
use Illuminate\Support\Str;

class CloudFileSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        $dummyFiles = [
            ['name' => 'report.pdf', 'size' => 204800],
            ['name' => 'profile.jpg', 'size' => 512000],
            ['name' => 'video.mp4', 'size' => 5242880],
            ['name' => 'document.docx', 'size' => 102400],
            ['name' => 'spreadsheet.xlsx', 'size' => 307200],
        ];

        foreach ($users as $user) {
            $folder = Folder::firstOrCreate([
                'user_id' => $user->id,
                'name' => 'Test Folder',
            ]);

            foreach ($dummyFiles as $file) {
                CloudFile::create([
                    'user_id' => $user->id,
                    'folder_id' => rand(0, 1) ? $folder->id : null,
                    'file_name' => $file['name'],
                    'file_path' => 'storage/dummy/' . Str::random(10) . '-' . $file['name'],
                    'encrypted_key' => encrypt(Str::random(16)),
                    'file_size' => $file['size'],
                ]);
            }
        }
    }
}
