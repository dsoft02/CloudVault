<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CloudFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'folder_id', 'file_name', 'file_path', 'encrypted_key', 'file_size', 'share_token'];


    protected $casts = [
        'file_size' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function getFileUrlAttribute()
    {
        return config('filesystems.disks.storj.url') . '/' . $this->file_path;
    }

    public function getDownloadUrlAttribute()
    {
        return $this->file_path && Storage::disk('storj')->exists($this->file_path)
            ? Storage::disk('storj')->temporaryUrl($this->file_path, now()->addMinutes(30))
            : null;
    }

    public function getReadableFileSizeAttribute()
    {
        return formatSize($this->file_size);
    }

    public function getIsDeletedAttribute()
    {
        return $this->trashed();
    }

    public function getShareTokenAttribute($value)
    {
        return $value ?: $this->generateShareToken();
    }

    protected function generateShareToken()
    {
        $this->update(['share_token' => $this->share_token ?: Str::uuid()]);
        return $this->share_token;
    }

}
