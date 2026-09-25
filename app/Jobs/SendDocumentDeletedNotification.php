<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDocumentDeletedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(
        public string $filename,
        public ?string $notifyEmail,
    ) {
        $this->onConnection('rabbitmq');
    }
    public function handle(): void
    {
        if (! $this->notifyEmail) {
            Log::warning('Document deleted notification skipped: no notification email configured.', [
                'filename' => $this->filename,
            ]);

            return;
        }

        Log::info('Document deleted notification would be sent.', [
            'to' => $this->notifyEmail,
            'filename' => $this->filename,
        ]);
    }
}
