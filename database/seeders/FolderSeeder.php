<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Folder;
use App\Models\User;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $defaultFolders = ['Videos', 'Images', 'Downloads', 'Documents'];

        foreach ($users as $user) {
            foreach ($defaultFolders as $folderName) {
                Folder::firstOrCreate([
                    'user_id' => $user->id,
                    'name' => $folderName,
                ]);
            }
        }
    }
}
