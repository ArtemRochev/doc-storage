<?php

namespace App\Models;

use App\Jobs\SendDocumentDeletedNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'filename',
        'path',
        'disk',
        'mime_type',
        'size',
        'uploaded_at',
        'user_id',
    ];

    protected $attributes = [
        'disk' => 'local',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'size' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Document $document) {
            $document->user_id ??= auth()->id();
            $document->uploaded_at ??= now();
        });

        static::saving(function (Document $document) {
            if (! $document->isDirty('path') || ! $document->path) {
                return;
            }

            $disk = Storage::disk($document->disk ?: 'local');

            if ($disk->exists($document->path)) {
                $document->mime_type = $disk->mimeType($document->path);
                $document->size = $disk->size($document->path);
            }
        });

        static::deleting(function (Document $document) {
            if ($document->path) {
                Storage::disk($document->disk ?: 'local')->delete($document->path);
            }
        });

        static::deleted(function (Document $document) {
            SendDocumentDeletedNotification::dispatch(
                $document->filename,
                config('documents.notification_email'),
            );
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
