<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'name', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(CloudFile::class);
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function getTotalSize()
    {
        $size = $this->files()->sum('file_size');

        $subfolders = $this->subfolders()->with('files')->get();

        foreach ($subfolders as $subfolder) {
            $size += $subfolder->getTotalSize();
        }

        return $size;
    }

    public function getReadableFolderSizeAttribute()
    {
        return formatSize($this->getTotalSize());
    }

    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $folder = $this;

        while ($folder && $folder->parent_id) {
            $breadcrumb[] = ['id' => $folder->id, 'name' => $folder->name];
            $folder = $folder->parent;
        }

        $breadcrumb[] = ['id' => $folder->id, 'name' => $folder->name];

        return array_reverse($breadcrumb);
    }

    public function getIsDeletedAttribute()
    {
        return $this->trashed();
    }

}
