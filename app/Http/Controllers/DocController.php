<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocController extends Controller
{
    public function download(Document $document): StreamedResponse
    {
        return Storage::disk($document->disk)->download($document->path, $document->filename);
    }
}
