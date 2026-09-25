<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:delete-expired-documents')]
#[Description('Delete documents uploaded more than 24 hours ago')]
class DeleteExpiredDocuments extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $documents = Document::where('uploaded_at', '<', now()->subDay())->get();

        if ($documents->isEmpty()) {
            $this->info('No expired documents to delete.');

            return self::SUCCESS;
        }

        $documents->each->delete();

        $this->info("Deleted {$documents->count()} expired document(s).");

        return self::SUCCESS;
    }
}
